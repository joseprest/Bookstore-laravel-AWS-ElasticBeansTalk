/* eslint-disable no-alert, react/no-string-refs */
const React = require('react-external');
const Panneau = require('panneau');
const _ = require('lodash-external');
const { t } = require('../../lib/trans');
const Immutable = require('immutable-external');
const PlaylistItem = require('../items/PlaylistItem');
const PlaylistItemAdd = require('../items/PlaylistItemAdd');
const TimelineActions = require('../../actions/TimelineActions');
const Requests = require('../../requests/index');

const ListActions = Panneau.Actions.List;
const AsyncTasksActions = Panneau.Actions.AsyncTasks;

const PlaylistList = React.createClass({
    propTypes: {
        addButton: React.PropTypes.bool,
        emptyMessage: React.PropTypes.string,
        noPlaylistMessage: React.PropTypes.string,
        screen: React.PropTypes.object,
        playlist: React.PropTypes.number,
        playlists: React.PropTypes.array,
        items: React.PropTypes.array,
        name: React.PropTypes.string,
    },

    mixins: [
        Panneau.Mixins.List,
        Panneau.Mixins.Paginator,
        Panneau.Mixins.Sortable,
    ],

    getDefaultProps() {
        return {
            addButton: true,
            emptyMessage: t('slideshow.content.currently_empty'),
            noPlaylistMessage: t('slideshow.please_select'),
            screen: null,
            playlist: null,
            playlists: [],
            items: [],
            name: '',
        };
    },

    getInitialState() {
        const playlistId = this.props.playlist;

        return {
            firstLoad: false,
            playlistItems: null,
            playlist: this.props.playlists.find(
                playlist => parseInt(playlist.id, 10) === parseInt(playlistId, 10)
            ),
            playlists: this.props.playlists,
        };
    },

    componentDidUpdate(prevProps, prevState) {
        const playlistChanged = prevState.playlist !== this.state.playlist;

        if (this.state.playlist && (playlistChanged || !this.state.firstLoad)) {
            this.loadItems();
        }
    },

    onClickCreate(e) {
        e.preventDefault();
        const name = prompt(t('slideshow.inputs.name'));

        if (name && name.length) {
            Requests.Playlists.create(this.props.screen.id, name)
                .then(this.onPlaylistCreated);
        }
    },

    onClickRename(e) {
        e.preventDefault();
        const name = prompt(t('slideshow.inputs.name'));

        if (name && name.length) {
            const request = Requests.Playlists.rename(this.state.playlist.id, name)
                // Since the name could be modified by the backend, we also update
                // the playlist after the request is done
                .then(this.onPlaylistUpdated);

            // Optimistic UI: we rename now
            this.state.playlist.name = name;
            this.onPlaylistUpdated(this.state.playlist);
            // Create new async task (to display the throbber)
            Panneau.dispatch(AsyncTasksActions.add(request));
        }
    },

    onClickDelete(e) {
        e.preventDefault();

        if (confirm(t('slideshow.deletion.confirmation'))) {
            const request = Requests.Playlists.delete(this.state.playlist.id);

            // Optimistic: update the UI before receiving the response
            this.onPlaylistDeleted(this.state.playlist);
            // Create new async task (to display the throbber)
            Panneau.dispatch(AsyncTasksActions.add(request));
        }
    },

    onClickAssociate(e) {
        e.preventDefault();

        const request = Requests.Playlists.attachToScreen(
            this.state.playlist.id,
            this.props.screen.id
        );

        // Optimistic UI: we immediately dissociate the playlist and the screen
        const playlist = this.state.playlist;
        this.associatePlaylistToScreen(playlist);
        this.deferredTimelineUpdate(request);
        this.onPlaylistUpdated(playlist);

        Panneau.dispatch(AsyncTasksActions.add(request));
    },

    onClickDissociate(e) {
        e.preventDefault();

        const request = Requests.Playlists.detachFromScreen(
                this.state.playlist.id,
                this.props.screen.id
            );

        // Optimistic UI: we immediately dissociate the playlist and the screen
        const playlist = this.state.playlist;
        this.dissociatePlaylistFromScreen();
        this.deferredTimelineUpdate(request);
        this.onPlaylistUpdated(playlist);

        Panneau.dispatch(AsyncTasksActions.add(request));
    },

    onClickPlaylist(e, playlist) {
        e.preventDefault();

        this.setState({
            playlist,
        });
    },

    onPlaylistCreated(playlist) {
        const currentPlaylists = Immutable.fromJS(this.state.playlists);
        const newPlaylists = currentPlaylists.push(playlist);

        this.setState(
            {
                playlist,
                playlists: newPlaylists.toJS(),
            },
            () => { this.updateTimeline(); }
        );
    },

    onPlaylistUpdated(playlist) {
        const screenId = _.get(this.props, 'screen.id');
        const screens = _.get(playlist, 'screens', []);
        const linked = this.isPlaylistLinkedToScreen(screens);
        const currentPlaylists = Immutable.fromJS(this.state.playlists);
        const newPlaylists = currentPlaylists.map((pl) => {
            if (parseInt(pl.get('id'), 10) === parseInt(playlist.id, 10)) {
                return Immutable.fromJS(playlist);
            }

            const playlistScreens = pl.get('screens').filter(
                screen => !linked || parseInt(screen.get('id'), 10) !== parseInt(screenId, 10)
            );

            return pl.set('screens', playlistScreens);
        });

        this.setState({
            playlist,
            playlists: newPlaylists.toJS(),
        });
    },

    onPlaylistDeleted(playlist) {
        const currentPlaylists = Immutable.fromJS(this.state.playlists);
        const index = currentPlaylists.findIndex(
            pl => parseInt(pl.get('id'), 10) === parseInt(playlist.id, 10)
        );

        if (index === -1) {
            return;
        }

        const newPlaylists = currentPlaylists.delete(index);

        this.setState(
            {
                playlist: null,
                playlists: newPlaylists.toJS(),
            },
            () => { this.updateTimeline(); }
        );
    },

    onItemsLoaded(items) {
        this.setState({
            firstLoad: true,
        });
        this.updateItems(items);
    },

    onItemClickRemove(it) {
        if (!confirm(t('slideshow.content.deletion.confirmation'))) {
            return;
        }

        const playlistId = _.get(this.state, 'playlist.id');
        const request = Requests.Playlists.removeItem(playlistId, it.id);

        // For optimistic UI, we remove now the item from the
        // list.
        this.onItemRemoved(it);
        Panneau.dispatch(AsyncTasksActions.add(request));
        // Trigger a timeline update after the request
        this.deferredTimelineUpdate(request);
    },

    onItemConditionChanging(promise) {
        this.deferredTimelineUpdate(promise);
    },

    onItemConditionChange(condition, it, index) {
        it.condition = condition;

        const items = Immutable.fromJS(this.props.items || []);
        const newItems = items.set(index, it);

        this.updateItems(newItems.toJS());
        this.updateTimeline();
    },

    onItemAdd(items) {
        const newItems = [
            ...this.props.items,
            ...items,
        ];

        this.updateItems(newItems);
        this.updateTimeline();
    },

    onItemRemoved(item) {
        const index = _.findIndex(
            this.props.items,
            it => String(it.id) === String(item.id)
        );

        if (index >= 0) {
            Panneau.dispatch(ListActions.removeItem(index, this.props.name));
        }
    },

    onSortableUpdate(e) {
        const ids = this.state.items.map(
            it => it.id
        );
        const playlistId = _.get(this.state, 'playlist.id');
        const request = Requests.Playlists.updateOrder(playlistId, ids)
            .then(_.bind(this.updateItems, this));
        const items = [
            ...this.state.items,
        ];

        _.set(items, `${e.newIndex}.loading`, true);

        // Optimistic UI: reorder now the items
        this.updateItems(items);
        Panneau.dispatch(AsyncTasksActions.add(request));
        this.deferredTimelineUpdate(request);
    },

    getListItemComponent() {
        return PlaylistItem;
    },

    getListItemProps(it, index) {
        return {
            editable: true,
            key: it.id,
            onClickRemove: () => { this.onItemClickRemove(it, index); },
            onConditionChanging: this.onItemConditionChanging,
            onConditionChange: (condition) => {
                this.onItemConditionChange(condition, it, index);
            },
        };
    },

    loadItems() {
        Requests.Playlists.getItems(this.state.playlist.id)
            .then(this.onItemsLoaded);
    },

    createPlaylistOnClick(playlist) {
        return (e) => { this.onClickPlaylist(e, playlist); };
    },

    isPlaylistLinkedToScreen(screens = _.get(this.state.playlist, 'screens', [])) {
        const screenId = _.get(this.props, 'screen.id');
        const linkedScreen = screens.find(
            screen => parseInt(screen.id, 10) === parseInt(screenId, 10)
        );

        return !!linkedScreen;
    },

    sortableOptions: {
        ref: 'list',
        model: 'items',
        handle: '.list-item-col-drag',
        filter: '.list-item-add',
        draggable: '.list-item',
        onUpdate: 'onSortableUpdate',
    },

    updateItems(items) {
        Panneau.dispatch(ListActions.updateData(items, this.props.name));
    },

    deferredTimelineUpdate(promise) {
        this.prepareTimelineForUpdate();
        this.deferredTimelineUpdatesCount = (this.deferredTimelineUpdatesCount || 0) + 1;

        promise.then(() => {
            this.deferredTimelineUpdatesCount = (this.deferredTimelineUpdatesCount || 1) - 1;

            if (this.deferredTimelineUpdatesCount <= 0) {
                this.deferredTimelineUpdatesCount = 0;
                this.updateTimeline();
            }
        });
    },

    prepareTimelineForUpdate() {
        Panneau.dispatch(TimelineActions.waitForNewData());
    },

    updateTimeline() {
        const screenId = _.get(this.props, 'screen.id');
        Panneau.dispatch(TimelineActions.updateForScreen(screenId));
    },

    dissociatePlaylistFromScreen() {
        const screenId = this.props.screen.id;
        const screens = _.get(this.state.playlist, 'screens', []);
        const newScreens = screens.filter(
            screen => String(screenId) !== String(screen.id)
        );

        this.state.playlist.screens = newScreens;
    },

    associatePlaylistToScreen() {
        const screens = _.get(this.state.playlist, 'screens', []);

        if (this.isPlaylistLinkedToScreen()) {
            return;
        }

        screens.push(this.props.screen);
        this.state.playlist.screens = screens;
    },

    renderToolbar() {
        const playlists = this.renderPlaylistsMenuItem();
        const name = this.state.playlist
            ? _.get(this.state.playlist, 'name')
            : t('slideshow.lists');
        const screens = _.get(this.state.playlist, 'screens', []);
        const playlistLinked = this.isPlaylistLinkedToScreen();

        let associateBtn;
        if (this.state.playlist) {
            associateBtn = playlistLinked
                ? (
                    <button
                        type="button"
                        className="btn btn-default"
                        onClick={this.onClickDissociate}
                    >
                        { t('slideshow.screen_association.actions.dissociate') }
                    </button>
                )
                : (
                    <button
                        type="button"
                        className="btn btn-default"
                        onClick={this.onClickAssociate}
                    >
                        { t('slideshow.screen_association.actions.associate') }
                    </button>
                );
        }

        let stats;
        if (this.state.playlist) {
            let screenCount;
            if (!screens.length) {
                screenCount = t('slideshow.screen_association.none');
            } else if (screens.length === 1) {
                screenCount = playlistLinked
                    ? t('slideshow.screen_association.current_screen_only')
                    : t('slideshow.screen_association.on_one_screen');
            } else {
                screenCount = t(
                    'slideshow.screen_association.on_nb_screens',
                    { nb: screens.length }
                );

                if (playlistLinked) {
                    screenCount += ` ${t('slideshow.screen_association.including_current')}`;
                }
            }

            stats = (
                <span className="stats">{ screenCount }</span>
            );
        }

        const optionsItems = [];
        if (this.state.playlist) {
            optionsItems.push(
                <li key="playlist-rename">
                    <a href="#" onClick={this.onClickRename}>
                        { t('slideshow.actions.rename') }
                    </a>
                </li>
            );
            optionsItems.push(
                <li key="playlist-delete">
                    <a href="#" onClick={this.onClickDelete}>
                        { t('slideshow.actions.delete') }
                    </a>
                </li>
            );
        }

        optionsItems.push(
            <li key="playlist-add">
                <a href="#" onClick={this.onClickCreate}>
                    { t('slideshow.actions.create') }
                </a>
            </li>
        );

        if (playlists && playlists.length) {
            optionsItems.push(
                <li key="playlist-options-divider" className="divider" />,
                <li key="playlist-title" className="dropdown-header">
                    { t('slideshow.lists') }
                </li>
            );
        }

        return (
            <div className="toolbar">
                <div className="pull-right text-right">
                    { associateBtn }
                </div>

                <div className="btn-toolbar">
                    <div className="btn-group">
                        <button
                            type="button"
                            className="btn btn-default dropdown-toggle"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            {name} <span className="caret" />
                        </button>
                        <ul className="dropdown-menu">
                            { optionsItems }
                            { playlists }
                        </ul>
                    </div>
                </div>
                { stats }
            </div>
        );
    },

    renderPlaylistsMenuItem() {
        const playlistId = _.get(this.state, 'playlist.id');
        const menuItems = [];
        let i = 0;

        this.state.playlists.forEach((playlist) => {
            const key = `playlist-item-${i}`;
            const screens = _.get(playlist, 'screens', []);
            const playlistLinked = this.isPlaylistLinkedToScreen(screens);
            let name = playlist.name;

            if (playlistLinked) {
                name = playlist.name;

                if (playlistLinked) {
                    name += ` (${t('slideshow.on_current_screen')})`;
                }
            }

            if (parseInt(playlist.id, 10) === parseInt(playlistId, 10)) {
                name = (
                    <strong> { name }</strong>
                );
            }

            menuItems.push(
                <li key={key}>
                    <a href="#" onClick={this.createPlaylistOnClick(playlist, i)}>
                        { name }
                    </a>
                </li>
            );

            i += 1;
        });

        return menuItems;
    },

    render() {
        const channelsParams = {};
        const screenId = _.get(this.props, 'screen.id', null);

        if (screenId) {
            channelsParams.screen_id = screenId;
        }

        let items;
        if (this.state.playlist && this.props.items) {
            items = this.renderListItems(this.props.items);
        } else {
            items = this.renderListItems([]);
        }

        const playlistLinked = this.isPlaylistLinkedToScreen();

        // Add button
        let addButton;
        if (this.props.addButton && this.state.playlist) {
            addButton = (
                <PlaylistItemAdd
                    bubblesSelectChannelsParams={channelsParams}
                    playlist={this.state.playlist}
                    currentItems={this.props.items}
                    onAdd={this.onItemAdd}
                />
            );
        }

        let associateMessage;
        if (this.state.playlist && !playlistLinked) {
            associateMessage = (
                <div className="list-item text-center">
                    <div className="alert alert-warning">
                        { t('slideshow.not_active_on_screen') }
                    </div>
                </div>
            );
        }

        if (!items.length && this.props.emptyMessage) {
            items = (
                <div className="list-item list-item-empty">
                    { this.state.playlist
                        ? this.props.emptyMessage
                        : this.props.noPlaylistMessage }
                </div>
            );
        }

        const className = 'list list-rows list-playlist';
        const toolbar = this.renderToolbar();

        return (
            <div className={className}>
                { toolbar }

                { associateMessage }

                { addButton }

                <div ref="list" className="list-items">
                    { items }
                </div>
            </div>
        );
    },
});

module.exports = PlaylistList;
