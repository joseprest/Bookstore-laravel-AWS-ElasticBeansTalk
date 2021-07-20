/* eslint-disable react/no-unused-prop-types, no-alert */
const React = require('react-external');
const Panneau = require('panneau');
const { t } = require('../../lib/trans');
const Bubbles = require('./Bubbles');
const TimelineActions = require('../../actions/TimelineActions');
const Requests = require('../../requests/index');

const ModalActions = Panneau.Actions.Modal;
const ListActions = Panneau.Actions.List;

const BubblesPlaylistList = React.createClass({
    propTypes: {
        name: React.PropTypes.string.isRequired,
        layout: React.PropTypes.string,
        addButton: React.PropTypes.bool,
        emptyMessage: React.PropTypes.string,
        screen_id: React.PropTypes.number,
        playlist_id: React.PropTypes.number,
    },

    getDefaultProps() {
        return {
            layout: 'playlist',
            addButton: true,
            emptyMessage: null,
            screen_id: null,
            playlist_id: null,
        };
    },

    getInitialState() {
        return {
            bubblesToAdd: null,
            filtersToAdd: null,
        };
    },

    onAdd(data = {}) {
        const bubbles = data.bubbles || null;
        const filters = data.filters || null;

        this.setState(
            {
                filtersToAdd: filters,
                bubblesToAdd: bubbles,
            },
            () => {
                Panneau.dispatch(ModalActions.openModal('Conditions', {
                    bubbles,
                    filters,
                    onComplete: this.onModalComplete,
                    onClose: this.onModalClose,
                }));
            }
        );
    },

    onBubbleClickRemove(bubble, index) {
        if (!confirm(t('slideshow.content.deletion.confirmation'))) {
            return;
        }

        Requests.Playlists.removeBubble(this.props.playlist_id, index)
            .then(this.onBubbleRemoved);
    },

    onModalComplete(condition) {
        if (this.state.bubblesToAdd) {
            Requests.Playlists.addBubblesWithCondition(
                this.props.playlist_id,
                this.state.bubblesToAdd,
                condition
            ).then(this.onBubbleAdded);
        } else if (this.state.filtersToAdd) {
            Requests.Playlists.addFiltersWithCondition(
                this.props.playlist_id,
                this.state.filtersToAdd,
                condition
            ).then(this.onBubbleAdded);
        }
    },

    onOrderChange(items) {
        const ids = items.map(
            it => it.playlist_item_id
        );

        Requests.Playlists.updateOrder(this.props.playlist_id, ids)
            .then(this.onOrderUpdated);
    },

    onModalClose() {
        this.setState({
            filtersToAdd: null,
            bubblesToAdd: null,
        });
    },

    onBubbleRemoved(bubbles) {
        Panneau.dispatch(ListActions.updateData(bubbles, this.props.name));
        Panneau.dispatch(TimelineActions.updateForScreen(this.props.screen_id));
    },

    onBubbleAdded(newBubbles) {
        const bubbles = !Array.isArray(newBubbles) ? [newBubbles] : newBubbles;

        this.setState(
            {
                filtersToAdd: null,
                bubblesToAdd: null,
            },
            () => {
                Panneau.dispatch(ListActions.addItems(bubbles, this.props.name));
                Panneau.dispatch(ModalActions.closeModal('Conditions'));
                Panneau.dispatch(TimelineActions.updateForScreen(this.props.screen_id));
            }
        );
    },

    onBubbleConditionChange() {
        Panneau.dispatch(TimelineActions.updateForScreen(this.props.screen_id));
    },

    onOrderUpdated(bubbles) {
        Panneau.dispatch(ListActions.updateData(bubbles, this.props.name));
        Panneau.dispatch(TimelineActions.updateForScreen(this.props.screen_id));
    },

    render() {
        const channelsParams = {};

        if (this.props.screen_id) {
            channelsParams.screen_id = this.props.screen_id;
        }

        return (
            <Bubbles
                {...this.props}
                bubblesSelectChannelsParams={channelsParams}
                onAdd={this.onAdd}
                onBubbleClickRemove={this.onBubbleClickRemove}
                onOrderChange={this.onOrderChange}
                onBubbleConditionChange={this.onBubbleConditionChange}
            />
        );
    },
});

module.exports = BubblesPlaylistList;
