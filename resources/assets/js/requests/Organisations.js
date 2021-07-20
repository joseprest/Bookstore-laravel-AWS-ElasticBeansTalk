const graphql = require('./graphql');
const Fragments = require('./Fragments');

module.exports = {
    linkScreen(authCode) {
        return graphql(`
            mutation Mutation<queryArgs>{
                data: organisationLinkScreen<objectArgs>
                {
                    ...screenFields
                }
            }`,
            {
                auth_code: authCode,
            },
            {
                fragments: [Fragments.screenFields],
            }
        ).then(result => result.data);
    },

    createScreen(name) {
        return graphql(`
            mutation Mutation<queryArgs>{
                data: organisationCreateScreen<objectArgs>
                {
                    ...screenFields
                }
            }`,
            {
                name,
            },
            {
                fragments: [Fragments.screenFields],
            }
        ).then(result => result.data);
    },

    inviteUser(email, roleId) {
        return graphql(`
            mutation Mutation<queryArgs>{
                data: organisationInviteUser<objectArgs>
                {
                    ...invitationFields
                }
            }`,
            {
                email,
                role_id: roleId,
            },
            {
                fragments: [Fragments.invitationFields],
            }
        ).then(result => result.data);
    },

    updateUser(user, roleId) {
        const userId = (typeof user === 'object' ? user.id : user);
        return graphql(`
            mutation Mutation<queryArgs>{
                data: organisationUpdateUser<objectArgs>
                {
                    ...userFields
                }
            }`,
            {
                user_id: userId,
                role_id: roleId,
            }, {
                fragments: [Fragments.userFields],
            }
        ).then(result => result.data);
    },

    removeUser(user) {
        const userId = (typeof user === 'object' ? user.id : user);
        return graphql(`
            mutation Mutation<queryArgs>{
                data: organisationRemoveUser<objectArgs>
                {
                    ...userFields
                }
            }`,
            {
                user_id: userId,
            },
            {
                fragments: [Fragments.userFields],
            }
        ).then(result => result.data);
    },

    updateInvitation(invitation, roleId) {
        const invitationId = (typeof invitation === 'object' ? invitation.id : invitation);
        return graphql(`
            mutation Mutation<queryArgs>{
                data: organisationUpdateInvitation<objectArgs>
                {
                    ...invitationFields
                }
            }`,
            {
                invitation_id: invitationId,
                role_id: roleId,
            },
            {
                fragments: [Fragments.invitationFields],
            }
        ).then(result => result.data);
    },

    removeInvitation(invitation) {
        const invitationId = (typeof invitation === 'object' ? invitation.id : invitation);
        return graphql(`
            mutation Mutation<queryArgs>{
                data: organisationRemoveInvitation<objectArgs>
                {
                    ...invitationFields
                }
            }`,
            {
                invitation_id: invitationId,
            },
            {
                fragments: [Fragments.invitationFields],
            }
        ).then(result => result.data);
    },
};
