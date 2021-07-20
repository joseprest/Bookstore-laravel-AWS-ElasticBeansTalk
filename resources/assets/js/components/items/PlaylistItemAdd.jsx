/* eslint-disable react/no-unused-prop-types */
const React = require('react-external');
const Panneau = require('panneau');
const _ = require('lodash-external');
const { t } = require('../../lib/trans');

const ModalActions = Panneau.Actions.Modal;
const Requests = require('../../requests/index');

const PlaylistItemAdd = React.createClass({
    propTypes: {
        playlist: React.PropTypes.object,
        bubblesSelectChannelsParams: React.PropTypes.object,
        onAdd: React.PropTypes.func,
    },

    getDefaultProps() {
        return {
            playlist: {},
            bubblesSelectChannelsParams: null,
            onAdd: null,
        };
    },

    getInitialState() {
        return {
            bubblesToAdd: null,
            filtersToAdd: null,
        };
    },

    onClick(e) {
        e.preventDefault();

        this.setState(
            {
                filtersToAdd: null,
                bubblesToAdd: null,
            },
            () => {
                Panneau.dispatch(ModalActions.openModal('BubblesSelect', {
                    channelsParams: this.props.bubblesSelectChannelsParams,
                    onComplete: this.onBubblesModalComplete,
                }));
            }
        );
    },

    onBubblesModalComplete(result) {
        Panneau.dispatch(ModalActions.closeModal('BubblesSelect'));

        const bubbles = _.get(result, 'bubbles', null);
        const filters = _.get(result, 'filters', null);
        const playlistId = _.get(this.props, 'playlist.id');

        if (filters) {
            Requests.Playlists.addFilters(playlistId, filters)
                .then(this.onItemAdded);
        } else {
            Requests.Playlists.addBubbles(playlistId, bubbles)
                .then(this.onItemAdded);
        }
    },

    onConditionModalComplete(condition) {
        const playlistId = _.get(this.props, 'playlist.id');

        if (this.state.bubblesToAdd) {
            Requests.Playlists.addBubblesWithCondition(
                playlistId,
                this.state.bubblesToAdd,
                condition
            ).then(this.onItemAdded);
        } else if (this.state.filtersToAdd) {
            Requests.Playlists.addFiltersWithCondition(
                playlistId,
                this.state.filtersToAdd,
                condition
            ).then(this.onItemAdded);
        }
    },

    onItemAdded(item) {
        Panneau.dispatch(ModalActions.closeModal('Conditions'));
        const items = !Array.isArray(item) ? [item] : item;

        this.setState(
            {
                filtersToAdd: null,
                bubblesToAdd: null,
            },
            () => {
                this.props.onAdd(items);
            }
        );
    },

    render() {
        return (
            <div className="list-item list-item-add text-center">
                <a href="#" className="btn btn-default" onClick={this.onClick}>
                    <span className="glyphicon glyphicon-plus" />
                    <span>{ t('slideshow.content.actions.add_slides') }</span>
                </a>
            </div>
        );
    },
});

module.exports = PlaylistItemAdd;
