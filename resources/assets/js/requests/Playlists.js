const graphql = require('./graphql');
const Fragments = require('./Fragments');

module.exports = {
    create(screen, name) {
        const screenId = (typeof screen === 'object' ? screen.id : screen);

        return graphql(`
            mutation Mutation<queryArgs>{
                playlistCreate<objectArgs>
                {
                    ...playlistFields
                }
            }`,
            {
                name,
                screen_id: screenId,
            },
            {
                fragments: [Fragments.playlistFields],
            }
        ).then(result => result.playlistCreate);
    },

    rename(playlist, name) {
        const playlistId = (typeof playlist === 'object' ? playlist.id : playlist);

        return graphql(`
            mutation Mutation<queryArgs>{
                playlistRename<objectArgs>
                {
                    ...playlistFields
                }
            }`,
            {
                name,
                playlist_id: playlistId,
            },
            {
                fragments: [Fragments.playlistFields],
            }
        ).then(result => result.playlistRename);
    },

    delete(playlist) {
        const playlistId = (typeof playlist === 'object' ? playlist.id : playlist);

        return graphql(`
            mutation Mutation<queryArgs>{
                playlistDelete<objectArgs>
                {
                    ...playlistFields
                }
            }`,
            {
                playlist_id: playlistId,
            },
            {
                fragments: [Fragments.playlistFields],
            }
        ).then(result => result.playlistDelete);
    },

    attachToScreen(playlist, screen) {
        const screenId = (typeof screen === 'object' ? screen.id : screen);
        const playlistId = (typeof playlist === 'object' ? playlist.id : playlist);

        return graphql(`
            mutation Mutation<queryArgs>{
                screenAttachPlaylist<objectArgs>
                {
                    ...playlistFields
                }
            }`,
            {
                playlist_id: playlistId,
                screen_id: screenId,
            },
            {
                fragments: [Fragments.playlistFields],
            }
        ).then(result => result.screenAttachPlaylist);
    },

    detachFromScreen(playlist, screen) {
        const screenId = (typeof screen === 'object' ? screen.id : screen);
        const playlistId = (typeof playlist === 'object' ? playlist.id : playlist);

        return graphql(`
            mutation Mutation<queryArgs>{
                screenDetachPlaylist<objectArgs>
                {
                    ...playlistFields
                }
            }`,
            {
                playlist_id: playlistId,
                screen_id: screenId,
            },
            {
                fragments: [Fragments.playlistFields],
            }
        ).then(result => result.screenDetachPlaylist);
    },

    getItems(playlist) {
        const playlistId = (typeof playlist === 'object' ? playlist.id : playlist);

        return graphql(`
            query Query<queryArgs>{
                playlistItems<objectArgs>
                {
                    ...playlistItemsFields
                }
            }`,
            {
                playlist_id: playlistId,
            },
            {
                fragments: [
                    Fragments.playlistItemsFields,
                    Fragments.bubbleFields,
                    Fragments.conditionFields,
                ],
            }
        ).then(result => result.playlistItems);
    },

    saveCondition(condition, id) {
        const baseParams = id ? { condition_id: id } : {};
        const params = {
            ...baseParams,
            ...condition,
        };

        return graphql(`
            mutation Mutation<queryArgs>{
                saveCondition<objectArgs>
                {
                    ...conditionFields
                }
            }`,
            params,
            {
                fragments: [Fragments.conditionFields],
            }
        ).then(result => result.saveCondition);
    },

    addBubblesWithCondition(playlist, bubbles, condition) {
        const playlistId = (typeof playlist === 'object' ? playlist.id : playlist);
        const conditionId = (typeof condition === 'object' ? condition.id : condition);
        const bubblesIds = bubbles.map(bubble => bubble.id);

        return graphql(`
            mutation Mutation<queryArgs>{
                data: playlistAddBubbles<objectArgs>
                {
                    ...playlistItemFields
                }
            }`,
            {
                playlist_id: playlistId,
                bubble_ids: bubblesIds,
                condition_id: conditionId,
            },
            {
                argsTypes: {
                    playlist_id: 'String',
                    bubble_ids: '[String]',
                    condition_id: 'String',
                },
                fragments: [
                    Fragments.playlistItemFields,
                    Fragments.bubbleFields,
                    Fragments.conditionFields,
                ],
            }
        ).then(result => result.data);
    },

    addFiltersWithCondition(playlist, filters, condition) {
        const playlistId = (typeof playlist === 'object' ? playlist.id : playlist);
        const conditionId = (typeof condition === 'object' ? condition.id : condition);

        return graphql(`
            mutation Mutation<queryArgs>{
                data: playlistAddFilters<objectArgs>
                {
                    ...playlistItemFields
                }
            }`,
            {
                playlist_id: playlistId,
                filters: JSON.stringify(filters),
                condition_id: conditionId,
            },
            {
                argsTypes: {
                    playlist_id: 'String',
                    filters: 'String',
                    condition_id: 'String',
                },
                fragments: [
                    Fragments.playlistItemFields,
                    Fragments.bubbleFields,
                    Fragments.conditionFields,
                ],
            }
        ).then(result => result.data);
    },

    addBubbles(playlist, bubbles) {
        const playlistId = (typeof playlist === 'object' ? playlist.id : playlist);
        const bubblesIds = bubbles.map(bubble => bubble.id);

        return graphql(`
            mutation Mutation<queryArgs>{
                data: playlistAddBubbles<objectArgs>
                {
                    ...playlistItemFields
                }
            }`,
            {
                playlist_id: playlistId,
                bubble_ids: bubblesIds,
            },
            {
                argsTypes: {
                    playlist_id: 'String',
                    bubble_ids: '[String]',
                },
                fragments: [
                    Fragments.playlistItemFields,
                    Fragments.bubbleFields,
                    Fragments.conditionFields,
                ],
            }
        ).then(result => result.data);
    },

    addFilters(playlist, filters) {
        const playlistId = (typeof playlist === 'object' ? playlist.id : playlist);

        return graphql(`
            mutation Mutation<queryArgs>{
                data: playlistAddFilters<objectArgs>
                {
                    ...playlistItemFields
                }
            }`,
            {
                playlist_id: playlistId,
                filters: JSON.stringify(filters),
            },
            {
                argsTypes: {
                    playlist_id: 'String',
                    filters: 'String',
                    condition_id: 'String',
                },
                fragments: [
                    Fragments.playlistItemFields,
                    Fragments.bubbleFields,
                    Fragments.conditionFields,
                ],
            }
        ).then(result => result.data);
    },

    removeItem(playlist, it) {
        const playlistId = (typeof playlist === 'object' ? playlist.id : playlist);
        const itemId = (typeof it === 'object' ? it.id : it);

        return graphql(`
            mutation Mutation<queryArgs>{
                data: playlistRemoveBubble<objectArgs>
                {
                    ...playlistItemFields
                }
            }`,
            {
                playlist_id: playlistId,
                item_id: itemId,
            },
            {
                argsTypes: {
                    playlist_id: 'String',
                    item_id: 'String',
                },
                fragments: [
                    Fragments.playlistItemFields,
                    Fragments.bubbleFields,
                    Fragments.conditionFields,
                ],
            }
        ).then(result => result.data);
    },

    updateOrder(playlist, ids) {
        const playlistId = (typeof playlist === 'object' ? playlist.id : playlist);

        return graphql(`
            mutation Mutation<queryArgs>{
                data: playlistUpdateOrder<objectArgs>
                {
                    ...playlistItemFields
                }
            }`,
            {
                ids,
                playlist_id: playlistId,
            },
            {
                argsTypes: {
                    playlist_id: 'String',
                    ids: '[Int]',
                },
                fragments: [
                    Fragments.playlistItemFields,
                    Fragments.bubbleFields,
                    Fragments.conditionFields,
                ],
            }
        ).then(result => result.data);
    },
};
