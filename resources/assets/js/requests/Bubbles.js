const graphql = require('./graphql');
const Fragments = require('./Fragments');

module.exports = {
    loadForChannel(channel, params = {}) {
        const id = (typeof channel === 'object' ? channel.id : channel);
        const requestParams = {
            ...params,
            channel_id: id,
        };

        return graphql(`
            query Query<queryArgs>{
                data: bubblesPaginated<objectArgs>
                {
                    pagination {
                        ...paginationFields
                    }
                    items {
                        ...bubbleFields
                    }
                }
            }`,
            requestParams,
            {
                argsTypes: {
                    page: 'Int',
                },
                fragments: [
                    Fragments.paginationFields,
                    Fragments.bubbleFields,
                ],
            }
        ).then(result => result.data);
    },
};
