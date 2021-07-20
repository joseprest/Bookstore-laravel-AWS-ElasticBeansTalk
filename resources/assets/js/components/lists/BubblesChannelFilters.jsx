const React = require('react-external');
const _ = require('lodash-external');
const Immutable = require('immutable-external');
const { t } = require('../../lib/trans');
const Requests = require('../../requests/index');
const BubblesChannelList = require('./BubblesChannel');

const BubblesChannelFilters = React.createClass({
    propTypes: {
        screen: React.PropTypes.object,
        channel: React.PropTypes.object,
        filters: React.PropTypes.array,
        onBubblesLoaded: React.PropTypes.func,
        onChannelChange: React.PropTypes.func,
        onFiltersChange: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            screen: null,
            channel: null,
            filters: null,
            onBubblesLoaded: null,
            onChannelChange: null,
            onFiltersChange: null,
        };
    },

    getInitialState() {
        const filters = this.props.filters;

        return {
            filters,
            filtersEnabled: filters && filters.length,
            filtersWithValue: this.getFiltersWithValue(filters),
        };
    },

    componentDidUpdate(prevProps, prevState) {
        const filters = this.state.filtersEnabled ? this.state.filtersWithValue : [];
        const prevFilters = prevState.filtersEnabled ? prevState.filtersWithValue : [];
        const filtersKey = this.getFiltersKey(filters);
        const prevFiltersKey = this.getFiltersKey(prevFilters);

        if (filtersKey !== prevFiltersKey) {
            this.saveFilters(filters);
        }
    },

    onFiltersCheckboxChange(e) {
        const checked = e.currentTarget.checked;

        this.setState({
            filtersEnabled: checked,
            filters: !checked ? [] : this.state.filters,
        });
    },

    onBubblesLoaded(bubbles) {
        if (this.props.onBubblesLoaded) {
            this.props.onBubblesLoaded(bubbles);
        }
    },

    onBubblesFiltersChange(filters) {
        this.setState({
            filters,
            filtersWithValue: this.getFiltersWithValue(filters),
        });
    },

    onSettingsSaved(channel) {
        const filters = _.get(channel, 'screen_settings.filters');
        const currentValue = Immutable.fromJS(this.props.channel);
        const newValue = currentValue.setIn(['screen_settings', 'filters'], filters);
        const newChannel = newValue.toJS();

        if (this.props.onChannelChange) {
            this.props.onChannelChange(newChannel);
        }
    },

    getFiltersKey(filters) {
        return filters.map(
            filter => `${filter.name}_${filter.value}`
        ).sort().join('|');
    },

    getFiltersWithValue(filters) {
        if (!filters) {
            return [];
        }

        return filters.filter(
            filter => !!filter.value
        );
    },

    saveFilters(filters) {
        Requests.Screens.saveChannelSettings(
            this.props.screen,
            this.props.channel,
            {
                filters,
            },
        ).then(this.onSettingsSaved);

        if (this.props.onFiltersChange) {
            this.props.onFiltersChange(filters);
        }
    },

    render() {
        return (
            <div className="bubbles-channel-filters">
                <div className="form-group form-group-checkbox">
                    <label htmlFor="filtering_checkbox">
                        <input
                            id="filtering_checkbox"
                            type="checkbox"
                            checked={this.state.filtersEnabled}
                            onChange={this.onFiltersCheckboxChange}
                        />
                        { t('channel.filtering.only_show_following') }
                    </label>
                </div>
                <BubblesChannelList
                    {...this.props}
                    filters={Array.isArray(this.state.filters) ? this.state.filters : []}
                    onLoaded={this.onBubblesLoaded}
                    onFiltersChange={this.onBubblesFiltersChange}
                />
            </div>
        );
    },
});

module.exports = BubblesChannelFilters;
