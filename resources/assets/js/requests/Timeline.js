const graphql = require('./graphql');
const Fragments = require('./Fragments');

module.exports = {
    loadForScreen(screenId) {
        return graphql(`
            query QueryTimeline<queryArgs>{
                data: timeline<objectArgs>
                {
                    ...timelineFields
                }
            }`,
            {
                screen_id: screenId,
            },
            {
                argsTypes: {
                    screen_id: 'String!',
                },
                fragments: [
                    Fragments.timelineFields,
                    Fragments.bubbleFields,
                ],
            }).then(result => result.data);
    },
};
