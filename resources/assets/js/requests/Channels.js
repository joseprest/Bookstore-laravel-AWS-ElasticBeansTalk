const graphql = require('./graphql');
const Fragments = require('./Fragments');

module.exports = {
    load(params) {
        return graphql(`
            query Query<queryArgs>{
                data: channels<objectArgs>
                {
                    ...channelFieldsWithFilters
                }
            }`,
            params,
            {
                fragments: [
                    Fragments.channelFieldsWithFilters,
                ],
            }
        ).then(result => result.data);
    },

    loadForScreen(screen) {
        const screenId = (typeof screen === 'object' ? screen.id : screen);
        return this.load({
            screen_id: screenId,
        });
    },

    loadFiltersValues(channel, filters) {
        const channelId = (typeof channel === 'object' ? channel.id : channel);
        const queries = filters.map((filter) => {
            const filterName = filter.name;
            return `
                ${filterName}: channelFilterValues(id: "${channelId}", filter: "${filterName}")
                {
                    label
                    value
                }`;
        });

        return graphql(`
            query Query {
                ${queries.join('\n')}
            }`);
    },

    removeBubble(channel, bubble) {
        const channelId = (typeof channel === 'object' ? channel.id : channel);
        const bubbleId = (typeof bubble === 'object' ? bubble.id : bubble);

        return graphql(`
            mutation Mutation<queryArgs>{
                data: channelRemoveBubble<objectArgs>
                {
                    ...bubbleFields
                }
            }`,
            {
                channel_id: channelId,
                bubble_id: bubbleId,
            },
            {
                argsTypes: {
                    channel_id: 'String!',
                    bubble_id: 'String!',
                },
                fragments: [Fragments.bubbleFields],
            }
        ).then(result => result.data);
    },
};
