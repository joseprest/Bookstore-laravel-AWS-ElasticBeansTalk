/* eslint-disable react/no-string-refs */
const React = require('react-external');
const Panneau = require('panneau');
const _ = require('lodash-external');
const { t } = require('../../lib/trans');
const BubbleItem = require('../items/Bubble');
const BubbleAdd = require('../items/BubbleAdd');

const ListActions = Panneau.Actions.List;

const BubblesList = React.createClass({
    propTypes: {
        items: React.PropTypes.array,
        addButton: React.PropTypes.bool,
        layout: React.PropTypes.string,
        name: React.PropTypes.string,
        bubblesSelectChannelsParams: React.PropTypes.object,
        checkedBubbles: React.PropTypes.array,
        emptyMessage: React.PropTypes.string,
        paginator: React.PropTypes.object,
        getListItemProps: React.PropTypes.func,
        onBubbleConditionChange: React.PropTypes.func,
        onBubbleClickRemove: React.PropTypes.func,
        onBubbleClickDelete: React.PropTypes.func,
        onBubbleCheck: React.PropTypes.func,
        onBubbleClickAdd: React.PropTypes.func,
        onBubbleRemove: React.PropTypes.func,
        onOrderChange: React.PropTypes.func,
        onAdd: React.PropTypes.func,
    },

    mixins: [
        Panneau.Mixins.List,
        Panneau.Mixins.Paginator,
        Panneau.Mixins.Sortable,
    ],

    getDefaultProps() {
        return {
            items: [],
            addButton: false,
            layout: 'bubble',
            name: null,
            bubblesSelectChannelsParams: null,
            checkedBubbles: [],
            emptyMessage: t('channel.no_content'),
            paginator: null,
            getListItemProps: null,
            onBubbleConditionChange: null,
            onBubbleClickRemove: null,
            onBubbleClickDelete: null,
            onBubbleCheck: null,
            onBubbleClickAdd: null,
            onBubbleRemove: null,
            onOrderChange: null,
            onAdd: null,
        };
    },

    getInitialState() {
        return {
            checkedBubbles: this.props.checkedBubbles,
        };
    },

    componentWillReceiveProps(nextProps) {
        const state = {};
        let stateHasChanged = false;

        if (this.state.checkedBubbles !== nextProps.checkedBubbles) {
            state.checkedBubbles = nextProps.checkedBubbles;
            stateHasChanged = true;
        }

        if (stateHasChanged) {
            this.setState(state);
        }
    },

    componentDidUpdate() {
        /**
         * When the component updates, we check if the items
         * list changed (ex: page change, other channel, filter, ...).
         * If the list changed, we scroll back up the list.
         */
        if (this.props.items !== this.lastItems) {
            this.mainNode.scrollTop = 0;
            this.lastItems = this.props.items;
        }
    },

    onAdd(bubbles) {
        if (this.props.onAdd) {
            this.props.onAdd(bubbles);
            return;
        }

        Panneau.dispatch(ListActions.addItems(bubbles, this.props.name));
    },

    onBubbleCheck(bubble, checked) {
        if (this.props.onBubbleCheck) {
            this.props.onBubbleCheck(bubble, checked);
        }
    },

    onBubbleConditionChange(bubble, condition) {
        if (this.props.onBubbleConditionChange) {
            this.props.onBubbleConditionChange(bubble, condition);
        }
    },

    onBubbleClickAdd(bubble) {
        if (this.props.onBubbleClickAdd) {
            this.props.onBubbleClickAdd(bubble);
        }
    },

    onBubbleClickRemove(bubble, index) {
        if (this.props.onBubbleClickRemove) {
            this.props.onBubbleClickRemove(bubble, index);
            return;
        }

        Panneau.dispatch(ListActions.removeItem(index, this.props.name));

        if (this.props.onBubbleRemove) {
            this.props.onBubbleRemove(bubble);
        }
    },

    onBubbleClickDelete(bubble, index) {
        if (this.props.onBubbleClickDelete) {
            this.props.onBubbleClickDelete(bubble, index);
        }
    },

    onSortableUpdate() {
        Panneau.dispatch(ListActions.updateData(this.state.items, this.props.name));

        if (this.props.onOrderChange) {
            this.props.onOrderChange(this.state.items);
        }
    },

    getListItemComponent() {
        return BubbleItem;
    },

    getListItemProps(it, index, itemProps) {
        const props = this.props.getListItemProps
            ? this.props.getListItemProps(it, index, itemProps)
            : {};
        props.layout = this.props.layout;
        props.checked = !!_.find(this.state.checkedBubbles, 'id', it.id);
        props.onCheck = (checked, bubble) => {
            this.onBubbleCheck(bubble, checked);
        };
        props.onClickAdd = this.onBubbleClickAdd;
        props.onClickRemove = (bubble) => {
            this.onBubbleClickRemove(bubble, index);
        };
        props.onConditionChange = (condition) => {
            this.onBubbleConditionChange(it, condition);
        };
        props.onClickDelete = (bubble) => {
            this.onBubbleClickDelete(bubble, index);
        };

        return props;
    },

    sortableOptions: {
        ref: 'list',
        model: 'items',
        handle: '.list-item-col-drag',
        filter: '.list-item-add',
        draggable: '.list-item',
        onUpdate: 'onSortableUpdate',
    },

    /**
     * Used to store the previous list of items and used in
     * componentDidUpdate() to check if the list must be
     * scrolled back up.
     *
     * @type {null|array}
     */
    lastItems: null,

    render() {
        let items = this.renderListItems(this.props.items);
        const paginator = this.props.paginator
            ? this.renderPaginator(this.props.paginator)
            : null;

        // Add button
        let addButton = null;
        if (this.props.addButton) {
            addButton = (
                <BubbleAdd
                    bubblesSelectChannelsParams={this.props.bubblesSelectChannelsParams}
                    currentItems={this.props.items}
                    onAdd={this.onAdd}
                />
            );
        }

        if (!items.length && this.props.emptyMessage) {
            items = (
                <div className="list-item list-item-empty">
                    { this.props.emptyMessage }
                </div>
            );
        }

        let className = 'list list-rows list-bubbles';
        if (this.props.layout === 'playlist') {
            className += ' list-playlist';
        }

        return (
            <div className={className} ref={(node) => { this.mainNode = node; }}>
                <div ref="list" className="list-items">
                    { items }
                </div>
                { addButton }
                { paginator }
            </div>
        );
    },
});

module.exports = BubblesList;
