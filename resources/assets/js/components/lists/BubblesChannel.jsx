/* eslint-disable react/no-unused-prop-types */
const React = require('react-external');
const _ = require('lodash-external');
const Panneau = require('panneau');
const Immutable = require('immutable-external');
const { t } = require('../../lib/trans');
const BubblesList = require('./Bubbles');
const Requests = require('../../requests/index');
const Url = require('../../lib/url');

const Form = Panneau.Components.Form.Component;
const Paginator = Panneau.Components.Paginator;
const AsyncTasksActions = Panneau.Actions.AsyncTasks;

const BubblesChannelList = React.createClass({
    propTypes: {
        channel: React.PropTypes.object,
        filters: React.PropTypes.array,
        filtersEnabled: React.PropTypes.bool,
        checkedBubbles: React.PropTypes.array,
        paginatorBottom: React.PropTypes.bool,
        onLoadingStart: React.PropTypes.func,
        onBubblesLoaded: React.PropTypes.func,
        onFiltersChange: React.PropTypes.func,
        onChange: React.PropTypes.func,
        onClickAdd: React.PropTypes.func,
        onLoadingEnd: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            channel: null,
            filters: null,
            filtersEnabled: true,
            checkedBubbles: null,
            paginatorBottom: true,
            onLoadingStart: null,
            onBubblesLoaded: null,
            onFiltersChange: null,
            onChange: null,
            onClickAdd: null,
            onLoadingEnd: null,
        };
    },

    getInitialState() {
        const filters = this.props.filters ? Immutable.fromJS(this.props.filters) : null;
        const params = this.props.filters
            ? Immutable.fromJS(this.getParamsFromFilters(this.props.filters))
            : null;

        return {
            params,
            filters,
            checkedBubbles: this.props.checkedBubbles,
            bubbles: null,
            page: 1,
            filtersValues: {},
        };
    },

    componentDidMount() {
        const filtersWithValues = this.props.channel.bubbles_filters.filter(
            filter => filter.type === 'select'
        );

        if (filtersWithValues.length) {
            this.reloadFilterValues(filtersWithValues)
            .then(this.reloadBubbles);
        } else {
            this.reloadBubbles();
        }
    },

    componentWillReceiveProps(nextProps) {
        if (this.props.filters !== nextProps.filters) {
            const filters = nextProps.filters ? Immutable.fromJS(nextProps.filters) : null;
            const params = nextProps.filters
                ? Immutable.fromJS(this.getParamsFromFilters(nextProps.filters))
                : null;

            this.setState({
                filters,
                params,
            });
        }
    },

    componentDidUpdate(prevProps, prevState) {
        if (this.queryHasChanged(prevProps, prevState)) {
            const reset = this.channelHasChanged(prevProps, prevState);
            this.reloadBubbles(reset);
        }
    },

    onLoadingStart() {
        if (this.loadingCounter === 0 && this.props.onLoadingStart) {
            this.props.onLoadingStart();
        }

        this.loadingCounter += 1;
    },

    onLoadingEnd() {
        this.loadingCounter -= 1;

        if (this.loadingCounter <= 0 && this.props.onLoadingEnd) {
            this.loadingCounter = 0;
            this.props.onLoadingEnd();
        }
    },

    onBubblesLoaded(bubbles) {
        this.setState(
            {
                bubbles: bubbles.items,
                pagination: bubbles.pagination,
            },
            () => {
                if (this.props.onBubblesLoaded) {
                    this.props.onBubblesLoaded(bubbles);
                }
            }
        );
    },

    onPaginatorClickPage(e, page) {
        e.preventDefault();

        this.setState({
            page,
        });
    },

    onBubbleCheck(bubble) {
        let checkedBubbles = this.state.checkedBubbles || [];
        const current = checkedBubbles.find(
            it => String(it.id) === String(bubble.id)
        );

        if (!current) {
            checkedBubbles.push(bubble);
        } else {
            checkedBubbles = checkedBubbles.filter(
                it => String(it.id) !== String(current.id)
            );
        }

        this.setState(
            {
                checkedBubbles,
            },
            () => {
                if (this.props.onChange) {
                    this.props.onChange(this.state.checkedBubbles);
                }
            }
        );
    },

    onBubbleClickDelete(bubble) {
        this.deleteBubble(bubble);
    },

    /**
     * Executes the request to remove the bubble from the channel, triggers the Asyn tasks and
     * removes the bubble from the list.
     *
     * @param {Object} bubble
     */
    deleteBubble(bubble) {
        const request = Requests.Channels.removeBubble(this.props.channel, bubble);
        Panneau.dispatch(AsyncTasksActions.add(request));
        this.removeBubbleFromList(bubble);
    },

    /**
     * Removes the specified bubble from the list and updates the state.
     *
     * @param {Object} bubble
     */
    removeBubbleFromList(bubble) {
        const currentBubbles = this.state.bubbles;
        const newBubbles = currentBubbles.filter(
            current => current.id !== bubble.id
        );
        this.setState({
            bubbles: newBubbles,
        });
    },

    onFiltersFormFieldChange(name, value) {
        if (name === 'filters') {
            const bubblesParams = this.getParamsFromFilters(value);
            this.setState({
                params: bubblesParams ? Immutable.fromJS(bubblesParams) : null,
                filters: value ? Immutable.fromJS(value) : null,
            });

            if (this.props.onFiltersChange) {
                this.props.onFiltersChange(value);
            }
        }
    },

    onBubbleClickAdd(bubble) {
        if (this.props.onClickAdd) {
            this.props.onClickAdd(bubble);
        }
    },

    getListItemProps(it) {
        const props = {
            editable: _.get(this.props, 'channel.fields.settings.canAddBubbles', false),
        };

        if (props.editable) {
            props.editLink = Url.route('organisation.bubbles.edit', {
                screen_id: _.get(this.props, 'screen.id', null),
                channel_id: _.get(this.props, 'channel.id', null),
                bubble_id: _.get(it, 'id', null),
            });
        }

        return props;
    },

    getParamsFromFilters(filters = []) {
        const params = {};
        let hasParams = false;

        if (Array.isArray(filters)) {
            filters.forEach((filter) => {
                let value = null;

                if (_.isObject(filter.value) && !Array.isArray(filter.value)) {
                    value = _.get(filter, 'value.value');
                } else if (filter.value && filter.value.length) {
                    value = filter.value;
                }

                if (value && value.length) {
                    const values = Array.isArray(value) ? value : [value];
                    value = values.filter(
                        val => val.length > 0
                    );
                }

                if (value && value.length) {
                    params[`filter_${filter.name}`] = value;
                    hasParams = true;
                }
            });
        }

        return hasParams ? params : null;
    },

    channelHasChanged(prevProps) {
        const channelId = _.get(this.props, 'channel.id', '');
        const prevChannelId = _.get(prevProps, 'channel.id', '');

        return String(channelId) !== String(prevChannelId);
    },

    queryHasChanged(prevProps, prevState) {
        return (
            this.channelHasChanged(prevProps, prevState)
            || this.state.params !== prevState.params
            || this.state.page !== prevState.page
        );
    },

    reloadFilterValues(filters) {
        this.onLoadingStart();
        return Requests.Channels.loadFiltersValues(this.props.channel, filters)
            .then((filtersValues) => {
                this.setState({
                    filtersValues,
                });
                this.onLoadingEnd();
            });
    },

    reloadBubbles(reset = false) {
        const params = this.state.params ? this.state.params.toJS() : null;

        if (reset) {
            this.setState(
                {
                    params: null,
                    filters: null,
                    page: 1,
                    pagination: null,
                    checked: null,
                },
                () => { this.loadBubbles(this.props.channel, params); }
            );
        } else {
            this.loadBubbles(this.props.channel, params);
        }
    },

    loadBubbles(channel, params = {}) {
        if (params === null) {
            // eslint-disable-next-line no-param-reassign
            params = {};
        }

        this.onLoadingStart();
        params.page = this.state.page;

        Requests.Bubbles.loadForChannel(channel, params)
            .then((bubbles) => {
                this.onBubblesLoaded(bubbles);
                this.onLoadingEnd();
            })
            .catch(() => {
                this.onLoadingEnd();
            });
    },

    loadingCounter: 0,

    renderFiltersForm(filters, fields) {
        const filterFields = fields.map((filter) => {
            const clonedFilter = _.cloneDeep(filter);

            if (this.state.filtersValues[filter.name]) {
                clonedFilter.values = this.state.filtersValues[filter.name];
            }

            return clonedFilter;
        });

        const formData = {
            filters,
        };
        const formFields = [
            {
                name: 'filters',
                type: 'filters',
                filters: filterFields,
                channel: this.props.channel,
            },
        ];

        let block;
        if (!this.props.filtersEnabled) {
            block = (
                <div className="filters-block" />
            );
        }

        return (
            <div className="filters">
                <Form
                    name="bubbles.filters"
                    fields={formFields}
                    data={formData}
                    onFieldChange={this.onFiltersFormFieldChange}
                />
                { block }
            </div>
        );
    },

    renderCounter(pagination) {
        const bubblesCount = pagination.total;

        let countLabel;
        if (bubblesCount === 1) {
            countLabel = t('channel.filtering.match_one');
        } else if (bubblesCount > 1) {
            countLabel = t('channel.filtering.match_nb', { nb: bubblesCount });
        }

        if (countLabel) {
            countLabel = (
                <div className="bubbles-row bubbles-counter">
                    { countLabel }
                </div>
            );
        }

        return countLabel;
    },

    renderPaginator(pagination) {
        if (pagination.last_page > 1) {
            return (
                <div className="bubbles-row bubbles-paginator">
                    <Paginator
                        total={pagination.total}
                        perPage={pagination.per_page}
                        currentPage={pagination.current_page}
                        lastPage={pagination.last_page}
                        interval={5}
                        onClickPage={this.onPaginatorClickPage}
                        onClickNext={this.onPaginatorClickPage}
                        onClickPrevious={this.onPaginatorClickPage}
                    />
                </div>
            );
        }

        return null;
    },

    render() {
        if (!this.state.bubbles) {
            return null;
        }

        // Filters form
        let filtersForm = null;
        if (this.props.channel) {
            const filters = this.state.filters ? this.state.filters.toJS() : null;
            const filtersFields = this.props.channel.bubbles_filters;
            filtersForm = this.renderFiltersForm(filters, filtersFields);
        }

        // Counter and paginator
        let counter;
        let paginator;
        if (this.state.pagination) {
            counter = this.renderCounter(this.state.pagination);
            paginator = this.renderPaginator(this.state.pagination);
        }

        // Bubbles list
        const bubbles = this.state.bubbles || [];
        const props = _.omit(
            this.props,
            ['items', 'checkedBubbles', 'onBubbleCheck', 'onBubbleClickAdd']
        );
        const list = (
            <BubblesList
                {...props}
                items={bubbles}
                getListItemProps={this.getListItemProps}
                checkedBubbles={this.state.checkedBubbles}
                onBubbleCheck={this.onBubbleCheck}
                onBubbleClickAdd={this.onBubbleClickAdd}
                onBubbleClickDelete={this.onBubbleClickDelete}
            />
        );

        return (
            <div className="bubbles-channel">
                { filtersForm }
                { counter }
                { list }
                { paginator }
            </div>
        );
    },
});

module.exports = BubblesChannelList;
