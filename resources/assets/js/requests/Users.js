const graphql = require('./graphql');
const Fragments = require('./Fragments');

module.exports = {
    updateAvatar(user, picture) {
        const userId = (typeof user === 'object' ? user.id : user);
        const pictureId = (typeof picture === 'object' ? picture.id : picture);

        return graphql(`
            mutation Mutation<queryArgs>{
                data: userUpdateAvatar<objectArgs>
                {
                    ...userFields
                }
            }`,
            {
                user_id: userId,
                picture_id: pictureId,
            },
            {
                argsTypes: {
                    user_id: 'String',
                    picture_id: 'String',
                },
                fragments: [
                    Fragments.userFields,
                ],
            }
        ).then(result => result.data);
    },

    removeAvatar(user) {
        const userId = (typeof user === 'object' ? user.id : user);

        return graphql(`
            mutation Mutation<queryArgs>{
                data: userRemoveAvatar<objectArgs>
                {
                    ...userFields
                }
            }`,
            {
                user_id: userId,
            },
            {
                argsTypes: {
                    user_id: 'String',
                },
                fragments: [
                    Fragments.userFields,
                ],
            }
        ).then(result => result.data);
    },
};
