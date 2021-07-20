module.exports = {
    paginationFields: `
        fragment paginationFields on Pagination {
            total
            per_page
            current_page
            last_page
            from
            to
        }
    `,

    screenFields: `
        fragment screenFields on Screen {
            id
            snippet {
                title
                subtitle
                description
                picture {
                    link
                }
            }
        }
    `,

    playlistFields: `
        fragment playlistFields on Playlist {
            id
            name
            screens {
                id
            }
        }
    `,

    playlistItemsFields: `
        fragment playlistItemsFields on PlaylistItem {
            id
            order
            bubble {
                ...bubbleFields
            }
            condition {
                ...conditionFields
            }
        }
    `,

    userFields: `
        fragment userFields on User {
            id
            name
            email
            role {
                id
                name
            }
            avatar {
                width
                height
                link
            }
        }
    `,

    invitationFields: `
        fragment invitationFields on OrganisationInvitation {
            id
            name
            email
            role {
                id
                name
            }
        }
    `,

    timelineFields: `
        fragment timelineFields on Timeline {
            bubbles {
                ...bubbleFields
            }
            cycles {
                start
                end
                items {
                    id
                    bubble_id
                    duration
                }
            }
        }
    `,

    channelFields: `
        fragment channelFields on Channel {
            id
            snippet {
                title
                subtitle
                description
                picture {
                    link
                }
            }
        }
    `,

    channelFieldsWithFilters: `
        fragment channelFieldsWithFilters on Channel {
            id
            snippet {
                title
                subtitle
                description
                picture {
                    link
                }
            }
            bubbles_filters {
                name
                label
                type
            }
        }
    `,

    channelFieldsWithFiltersAndValues: `
        fragment channelFieldsWithFiltersAndValues on Channel {
            id
            snippet {
                title
                subtitle
                description
                picture {
                    link
                }
            }
            bubbles_filters {
                name
                label
                type
                ... on ChannelBubbleFilterSelect {
                    values {
                        label
                        value
                    }
                }
            }
        }
    `,

    screenChannelFields: `
        fragment screenChannelFields on Channel {
            screen_settings {
                filters {
                    name
                    value
                }
            }
        }
    `,

    screenPingFields: `
        fragment screenPingFields on ScreenPing {
            id

        }
    `,

    screenCommandFields: `
        fragment screenCommandFields on ScreenCommand {
            id
            command
            arguments
            output
            return_code
            executed
            sended
            executed_at
            sended_at
            created_at
        }
    `,

    bubbleFields: `
        fragment bubbleFields on Bubble {
            id
            type
            snippet {
                title
                subtitle
                description
                type
                picture {
                    width
                    height
                    link
                }
                picture_thumbnail: picture(filter: "thumbnail_snippet") {
                    width
                    height
                    link
                }
            }
        }
    `,

    playlistItemFields: `
        fragment playlistItemFields on PlaylistItem {
            id
            order
            bubble {
                ...bubbleFields
            }
            condition {
                ...conditionFields
            }
        }
    `,

    conditionFields: `
        fragment conditionFields on Condition {
            id
            name
            snippet {
                title
                subtitle
                description
            }

            fields {
                days
                daterange
                date
                time
            }
        }
    `,
};
