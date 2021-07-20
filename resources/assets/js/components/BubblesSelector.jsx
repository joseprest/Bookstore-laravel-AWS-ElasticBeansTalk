const React = require('react-external');
const _ = require('lodash-external');
const Requests = require('../requests/index');
const BubblesChannelList = require('./lists/BubblesChannel');
const LoadingBlock = require('./LoadingBlock');

const BubblesSelector = React.createClass({
    propTypes: {
        channelsParams: React.PropTypes.object,
        channels: React.PropTypes.array,
        checkedBubbles: React.PropTypes.array,
        onChange: React.PropTypes.func,
        onChannelChange: React.PropTypes.func,
        onFiltersChange: React.PropTypes.func,
        onClickAdd: React.PropTypes.func,
        onChannelsLoaded: React.PropTypes.func,
        onBubblesLoaded: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            channelsParams: null,
            channels: null,
            checkedBubbles: null,
            onChange: null,
            onChannelChange: null,
            onFiltersChange: null,
            onClickAdd: null,
            onChannelsLoaded: null,
            onBubblesLoaded: null,
        };
    },

    getInitialState() {
        return {
            channels: this.props.channels,
            channel: this.props.channels && this.props.channels.length
                ? this.props.channels[0]
                : null,
            loadingChannels: false,
            loadingBubbles: false,
        };
    },

    componentDidMount() {
        if (!this.state.channels) {
            this.loadChannels();
        }
    },

    componentWillReceiveProps(nextProps) {
        if (nextProps.checkedBubbles && this.state.checkedBubbles !== nextProps.checkedBubbles) {
            this.setState({
                checkedBubbles: nextProps.checkedBubbles,
            });
        }
    },

    componentDidUpdate(prevProps, prevState) {
        const channelId = String(_.get(this.state, 'channel.id', null));
        const prevChannelId = String(_.get(prevState, 'channel.id', null));

        if (channelId !== prevChannelId && this.props.onChannelChange) {
            this.props.onChannelChange(this.state.channel);
        }
    },

    onChannelsLoaded(channels) {
        this.setState({
            channels,
            channel: channels && channels.length ? channels[0] : null,
            loadingChannels: false,
        });

        if (this.props.onChannelsLoaded) {
            this.props.onChannelsLoaded(channels);
        }
    },

    onChannelsLoadError() {
        this.setState({
            loadingChannels: false,
        });
    },

    onChannelClick(channel) {
        this.setState({
            channel,
            bubbles: null,
        });
    },

    onBubblesClickAdd(bubble) {
        if (this.props.onClickAdd) {
            this.props.onClickAdd(bubble);
        }
    },

    /**
     * Called by the bubbles list when loading starts
     */
    onLoadingStart() {
        this.setState({
            loadingBubbles: true,
        });
    },

    /**
     * Called by the bubbles list when loading end
     */
    onLoadingEnd() {
        this.setState({
            loadingBubbles: false,
        });
    },

    /**
     * Called by the bubbles list when loading finishes
     */
    onBubblesLoaded(bubbles) {
        if (this.props.onBubblesLoaded) {
            this.props.onBubblesLoaded(bubbles);
        }
    },

    onBubblesChange(bubbles) {
        if (this.props.onChange) {
            this.props.onChange(bubbles);
        }
    },

    onBubblesFiltersChange(filters) {
        if (this.props.onFiltersChange) {
            this.props.onFiltersChange(filters);
        }
    },

    loadChannels() {
        this.setState(
            {
                channels: null,
                loadingChannels: true,
            },
            () => {
                Requests.Channels.load(this.props.channelsParams)
                    .then(this.onChannelsLoaded)
                    .catch(this.onChannelsLoadError);
            }
        );
    },

    renderChannels(channels) {
        return (
            <ul className="nav nav-pills nav-stacked">
                { channels.map(this.renderChannel) }
            </ul>
        );
    },

    renderChannel(channel) {
        let className = '';
        const key = `channel_${channel.id}`;
        const title = _.get(channel, 'snippet.title');
        const onClick = () => {
            this.onChannelClick(channel);
        };

        if (this.state.channel && this.state.channel.id === channel.id) {
            className += ' active';
        }

        return (
            <li key={key} role="presentation" className={className}>
                <a href="#" onClick={onClick}>{ title }</a>
            </li>
        );
    },

    render() {
        /*
         * "Main" loading
         * Shown above the whole modal content when loading the channels
         * list
         */
        let mainLoading = null;

        if (!this.state.channels || !this.state.channel || this.state.loadingChannels) {
            mainLoading = <LoadingBlock />;
        }

        /*
         * "Bubbles" loading
         * Shown only above the bubbles list when loading the bubbles
         * of a channel.
         */
        let bubblesLoading = null;

        if (this.state.loadingBubbles) {
            bubblesLoading = <LoadingBlock />;
        }

        // Channels menu
        let channelsMenu = null;

        if (this.state.channels) {
            channelsMenu = this.renderChannels(this.state.channels);
        }

        // Bubbles list
        let bubblesList = null;

        if (this.state.channel) {
            bubblesList = (
                <BubblesChannelList
                    layout="add"
                    paginatorBottom={false}
                    channel={this.state.channel}
                    checkedBubbles={this.props.checkedBubbles}
                    onLoadingStart={this.onLoadingStart}
                    onLoadingEnd={this.onLoadingEnd}
                    onBubblesLoaded={this.onBubblesLoaded}
                    onChange={this.onBubblesChange}
                    onClickAdd={this.onBubblesClickAdd}
                    onFiltersChange={this.onBubblesFiltersChange}
                />
            );
        }

        return (
            <div className="bubbles-selector">
                <div className="row">
                    { mainLoading }
                    <div className="col-sm-3 col-channels">
                        { channelsMenu }
                    </div>
                    <div className="col-sm-9 col-bubbles">
                        { bubblesLoading }
                        { bubblesList }
                    </div>
                </div>
            </div>
        );
    },
});

module.exports = BubblesSelector;
