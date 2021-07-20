const React = require('react-external');
const Panneau = require('panneau');
const _ = require('lodash-external');
const ChannelItem = require('../items/Channel');
const ChannelItemAdd = require('../items/ChannelAdd');
const Requests = require('../../requests/index');

const ListActions = Panneau.Actions.List;
const AsyncTasksActions = Panneau.Actions.AsyncTasks;

const ChannelsList = React.createClass({
    propTypes: {
        name: React.PropTypes.string.isRequired,
        items: React.PropTypes.array,
        screen: React.PropTypes.object,
        withAddButton: React.PropTypes.bool,
        withRemoveButton: React.PropTypes.bool,
    },

    mixins: [Panneau.Mixins.List],

    getDefaultProps() {
        return {
            screen: null,
            withAddButton: true,
            withRemoveButton: true,
            items: [],
        };
    },

    onAdd(channel) {
        const request = Requests.Screens.addChannel(this.props.screen.id, channel.id);

        // Optimistic: update the UI before receiving the response
        this.onChannelAdded(channel);
        // Create new async task (to display the throbber)
        Panneau.dispatch(AsyncTasksActions.add(request));

        // On error, remove the added channel
        request.catch(() => {
            this.onChannelRemoved(channel);
        });
    },

    onChannelChange(channel, index) {
        Panneau.dispatch(ListActions.updateItem(index, channel, this.props.name));
    },

    onChannelAdded(channel) {
        Panneau.dispatch(ListActions.addItem(channel, this.props.name));
    },

    onRemove(channel) {
        const request = Requests.Screens.removeChannel(this.props.screen.id, channel.id);

        // Optimistic: update the UI before receiving the response
        this.onChannelRemoved(channel);
        // Create new async task (to display the throbber)
        Panneau.dispatch(AsyncTasksActions.add(request));

        // On error, re-add the channel
        request.catch(() => {
            this.onChannelAdded(channel);
        });
    },

    onChannelRemoved(channel) {
        const index = _.findIndex(
            this.props.items,
            it => String(it.id) === String(channel.id)
        );

        if (index >= 0) {
            Panneau.dispatch(ListActions.removeItem(index, this.props.name));
        }
    },

    getListItemComponent() {
        return ChannelItem;
    },

    getListItemProps(channel, index) {
        const onChannelChange = () => {
            this.onChannelChange(channel, index);
        };

        return {
            screen: this.props.screen,
            onChange: onChannelChange,
            onRemove: this.onRemove,
            withRemoveButton: this.props.withRemoveButton,
        };
    },

    render() {
        const items = this.renderListItems(this.props.items);

        let addButton;
        if (this.props.withAddButton) {
            addButton = (
                <ChannelItemAdd
                    channelsAdded={this.props.items}
                    onAdd={this.onAdd}
                    onRemove={this.onRemove}
                />
            );
        }

        return (
            <div className="list list-thumbnails list-channels">
                { items }
                { addButton }
            </div>
        );
    },
});

module.exports = ChannelsList;
