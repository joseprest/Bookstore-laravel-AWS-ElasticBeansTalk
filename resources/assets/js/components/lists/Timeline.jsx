/* eslint-disable jsx-a11y/no-static-element-interactions */
const React = require('react-external');
const _ = require('lodash-external');
const $ = require('jquery-external');
const Panneau = require('panneau');
const Img = require('image');
const { t } = require('../../lib/trans');
const TimelineActions = require('../../actions/TimelineActions');

const TimeField = Panneau.Components.Fields.Time;

const Timeline = React.createClass({
    propTypes: {
        screen_id: React.PropTypes.number.isRequired,
        items: React.PropTypes.oneOfType([
            React.PropTypes.array,
            React.PropTypes.object,
        ]),
    },

    getDefaultProps() {
        return {
            items: {},
        };
    },

    getInitialState() {
        const currentTime = (new Date()).getTime();

        return {
            currentTime,
            items: this.props.items,
            loading: false,
            time: currentTime,
            filter: 'now',
            cyclesCount: 2,
            open: false,
        };
    },

    componentDidMount() {
        this.clockInterval = setInterval(this.onClockInterval, 500);

        $(window).on('resize', this.onResize);

        this.onResize();

        if (!this.props.items.length) {
            Panneau.dispatch(TimelineActions.updateForScreen(this.props.screen_id));
        }
    },

    componentWillReceiveProps(nextProps) {
        const itemsChanged = nextProps.items !== this.state.items;

        if (itemsChanged) {
            const loading = _.get(nextProps, 'items.type', '') === 'loading';

            this.setState({
                loading,
                items: !loading ? nextProps.items : this.state.items,
            });
        }
    },

    componentWillUnmount() {
        $(window).off('resize', this.onResize);

        if (this.clockInterval) {
            clearInterval(this.clockInterval);
        }
    },

    onResize() {
        const $el = $(this.mainElement);
        const width = $el.outerWidth();
        const $items = $el.find('.list-group-item');
        let cycleWidth = 0;

        $items.each((index, element) => {
            const $item = $(element);

            cycleWidth += $item.outerWidth();
            if ($item.is('.list-group-item-separator')) {
                // Break the each loop
                return false;
            }

            return true;
        });

        const cyclesCount = Math.max(Math.ceil(width / cycleWidth), 1) + 1;
        this.setState({
            cyclesCount,
        });
    },

    onToggleClick() {
        this.setState({
            open: !this.state.open,
        });
    },

    onFilterChange(e) {
        this.setState({
            filter: e.target.value,
            time: e.target.value === 'now'
                ? this.state.currentTime
                : this.state.time,
            open: true,
        });
    },

    onTimeChange(value) {
        const timeParts = value.split(':');
        const date = new Date();

        date.setHours(parseInt(timeParts[0], 10));
        date.setMinutes(parseInt(timeParts[1], 10));
        date.setSeconds(0);

        this.setState({
            time: date.getTime(),
        });
    },

    onClockInterval() {
        const newState = {
            currentTime: (new Date()).getTime(),
        };

        if (this.state.filter === 'now') {
            newState.time = newState.currentTime;
        }

        this.setState(newState);
    },

    getCurrentCycle(time) {
        // Find current cycle
        const cycles = _.get(this.state, 'items.cycles');
        let currentCycle = null;

        cycles.some((cycle) => {
            const startTime = (new Date(cycle.start * 1000)).getTime();
            const endTime = (new Date(cycle.end * 1000)).getTime();

            if (time >= startTime && time < endTime) {
                currentCycle = cycle;
                // breaks
                return true;
            }

            return false;
        });

        return currentCycle;
    },

    getCycleDuration(cycle) {
        return cycle.items.reduce(
            (prev, it) => prev + (it.duration * 1000),
            0
        );
    },

    getItemAtPosition(cycle, position) {
        // Find the item covering this position
        let currentPosition = 0;

        return cycle.items.reduce(
            (prevItem, it) => {
                currentPosition += it.duration;
                if (currentPosition > position) {
                    return prevItem;
                }
                return it;
            },
            cycle.items[0]
        );
    },

    renderTimeline() {
        const cycles = _.get(this.state, 'items.cycles', []);

        if (!cycles || !cycles.length) {
            return (
                <li className="list-group-item list-group-item-empty">
                    <div className="middle">
                        { t('slideshow.content.no_content') }
                    </div>
                </li>
            );
        }

        const items = [];
        let currentTime = this.state.time;
        let index = 0;

        for (let i = 0; i < this.state.cyclesCount; i += 1) {
            if (i > 0) {
                items.push(this.renderSeparator(i));
            }

            const currentCycle = this.getCurrentCycle(currentTime);

            if (currentCycle) {
                const cycleDuration = this.getCycleDuration(currentCycle);
                const cycleStartTime = currentCycle.start * 1000;
                const delta = currentTime - cycleStartTime;
                const position = delta % cycleDuration;
                const cyclesCount = Math.floor(delta / cycleDuration);
                const currentItem = this.getItemAtPosition(currentCycle, position);
                let itemCurrentTime = cycleStartTime + (cyclesCount * cycleDuration);

                for (let j = 0; j < currentCycle.items.length; j += 1) {
                    const it = currentCycle.items[j];
                    const current = it.id === currentItem.id;
                    items.push(this.renderItem(it, itemCurrentTime, current, index));
                    itemCurrentTime += it.duration * 1000;
                    index += 1;
                }

                currentTime += cycleDuration;
            }
        }

        return items;
    },

    renderItem(it, time, current, index) {
        const endTime = time + (it.duration * 1000);
        const date = new Date(time);
        const hours = date.getHours();
        const minutes = date.getMinutes();
        const seconds = date.getSeconds();
        let timeLabel = [];

        timeLabel.push(_.padLeft(hours, 2, '0'));
        timeLabel.push(_.padLeft(minutes, 2, '0'));
        timeLabel.push(_.padLeft(seconds, 2, '0'));
        timeLabel = timeLabel.join(':');

        const key = `item_${index}`;
        const bubbles = _.get(this.state, 'items.bubbles', []);
        const bubble = bubbles.find(
            currBubble => String(currBubble.id) === String(it.bubble_id)
        );
        let pictureLink = _.get(bubble || {}, 'snippet.picture.link');

        if (pictureLink && pictureLink.length) {
            pictureLink = Img.url(pictureLink, {
                thumbnail_snippet: true,
            });
        }
        const thumbnailStyle = {
            backgroundImage: `url("${pictureLink}")`,
        };

        let itemClassName = 'list-group-item';
        if (this.state.currentTime >= time && this.state.currentTime < endTime) {
            itemClassName += ' list-group-item-current';
            timeLabel = t('slideshow.now');
        }

        return (
            <li key={key} className={itemClassName}>
                <div className="timeline-thumbnail" style={thumbnailStyle} />
                <span>{ timeLabel }</span>
            </li>
        );
    },

    renderSeparator(index) {
        const key = `sep_${index}`;

        return (
            <li key={key} className="list-group-item list-group-item-separator">
                <div className="border-timeline">
                    <div className="list-group-item-separator-border" />
                    <div className="glyphicon glyphicon-retweet list-group-item-separator-icon" />
                    <div className="list-group-item-separator-border" />
                </div>
            </li>
        );
    },

    render() {
        const timeline = this.renderTimeline();
        const pickerStyle = {};

        if (this.state.filter !== 'time') {
            pickerStyle.display = 'none';
        }

        const date = new Date(this.state.time);
        const hours = date.getHours();
        const minutes = date.getMinutes();
        const time = [];

        time.push(_.padLeft(hours, 2, '0'));
        time.push(_.padLeft(minutes, 2, '0'));

        const timeValue = time.join(':');
        const upDownClassName = this.state.open ? 'down' : 'up';
        const toggleIconClassName = `glyphicon glyphicon glyphicon-menu-${upDownClassName}`;
        let timelineClassName = 'container-timeline timeline-slider';

        if (this.state.open) {
            timelineClassName += ' timeline-open';
        }

        let loading;
        if (this.state.loading) {
            loading = (
                <li className="loading">
                    <div className="inner">
                        <div className="middle">
                            { t('slideshow.updating') }
                        </div>
                    </div>
                </li>
            );
        }

        return (
            <div className={timelineClassName} ref={(node) => { this.mainElement = node; }}>

                <div className="timeline-header">
                    <button type="button" className="btn btn-toggle" onClick={this.onToggleClick}>
                        <span className={toggleIconClassName} />
                    </button>

                    <label
                        className="timeline-label"
                        htmlFor="timeline-select"
                        onClick={this.onToggleClick}
                    >
                        { t('slideshow.preview') }
                    </label>
                    <select
                        id="timeline-select"
                        className="form-control timeline-selector"
                        name="role"
                        value={this.state.filter}
                        onChange={this.onFilterChange}
                    >
                        <option value="now">{ t('slideshow.now') }</option>
                        <option value="time">{ t('slideshow.specific_time') }</option>
                    </select>
                    <div className="timeline-picker" style={pickerStyle}>
                        <TimeField value={timeValue} onChange={this.onTimeChange} />
                    </div>
                </div>

                <ul className="list-group list-group-timeline">
                    { loading }
                    { timeline }
                </ul>

            </div>
        );
    },
});

module.exports = Timeline;
