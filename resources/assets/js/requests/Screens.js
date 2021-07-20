const graphql = require('./graphql');
const Fragments = require('./Fragments');

module.exports = {
    addChannel(screen, channel) {
        const screenId = (typeof screen === 'object' ? screen.id : screen);
        const channelId = (typeof channel === 'object' ? channel.id : channel);

        return graphql(`
            mutation Mutation<queryArgs>{
                data: screenAddChannel<objectArgs>
                {
                    ...channelFields
                }
            }`,
            {
                channel_id: channelId,
                screen_id: screenId,
            },
            {
                argsTypes: {
                    screen_id: 'String!',
                    channel_id: 'String!',
                },
                fragments: [Fragments.channelFields],
            }
        ).then(result => result.data);
    },

    removeChannel(screen, channel) {
        const screenId = (typeof screen === 'object' ? screen.id : screen);
        const channelId = (typeof channel === 'object' ? channel.id : channel);

        return graphql(`
            mutation Mutation<queryArgs>{
                data: screenRemoveChannel<objectArgs>
                {
                    ...channelFields
                }
            }`,
            {
                channel_id: channelId,
                screen_id: screenId,
            },
            {
                argsTypes: {
                    screen_id: 'String!',
                    channel_id: 'String!',
                },
                fragments: [Fragments.channelFields],
            }
        ).then(result => result.data);
    },

    saveChannelSettings(screen, channel, settings) {
        const screenId = (typeof screen === 'object' ? screen.id : screen);
        const channelId = (typeof channel === 'object' ? channel.id : channel);
        const params = {
            screen_id: screenId,
            channel_id: channelId,
            settings: JSON.stringify(settings),
        };

        return graphql(`
            mutation Mutation<queryArgs>{
                data: screenSaveChannelSettings<objectArgs>
                {
                    ...screenChannelFields
                }
            }`,
            params, {
                argsTypes: {
                    screen_id: 'String!',
                    channel_id: 'String!',
                    settings: 'String',
                },
                fragments: [Fragments.screenChannelFields],
            }
        ).then(result => result.data);
    },

    sendCommand(screen, command, args) {
        const screenId = (typeof screen === 'object' ? screen.id : screen);
        const params = {
            command,
            screen_id: screenId,
            arguments: JSON.stringify(args),
        };

        return graphql(`
            mutation Mutation<queryArgs>{
                data: screenSendCommand<objectArgs>
                {
                    ...screenCommandFields
                }
            }`,
            params, {
                argsTypes: {
                    screen_id: 'String!',
                    command: 'String!',
                    arguments: 'String',
                },
                fragments: [Fragments.screenCommandFields],
            }
        ).then(result => result.data);
    },
};
