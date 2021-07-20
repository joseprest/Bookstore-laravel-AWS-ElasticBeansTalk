fragment bubbleFields on Bubble {
    id
    type
    type_name
    channel_id
    snippet {
        title
        subtitle
        description
        summary
        picture {
            width
            height
            link
        }
        background_picture: picture(filter: "background_blur") {
            width
            height
            link
        }
        thumbnail_picture: picture(filter: "thumbnail") {
            width
            height
            link
        }
    }
    filters {
        ... on BubbleBookFilters {
            author
            collection
        }
        ... on BubblePublicationFilters {
            author
            publisher
            collection
            categories
        }

        ... on BubbleEventFilters {
            group
            category
            venue
            date
        }

        ... on BubbleBanqCardFilters {
            subjects
            year
            location
        }

        ... on BubbleBanqPhotoFilters {
            subjects
            year
        }

        ... on BubbleBanqBookFilters {
            genres
            subjects
            characters
            locations
            awards
        }

        ... on BubbleBanqQuestionFilters {
            question_category
        }

        ... on BubbleQuizzQuestionFilters {
            question_category
        }

        ... on BubbleLocationFilters {
            location
        }
    }
    fields {
        ... on BubbleAnnouncementFields {
            published_at {
                type
                label
                value

                ... on BubbleDateField {
                    date
                }
            }
        }
        ... on BubbleBookFields {
            authors {
                type
                label
                value
            }
            categories {
                type
                label
                value
            }

            publisher {
                type
                label
                value
            }
            date {
                type
                label
                value

                ... on BubbleDateField {
                    date
                }
            }
            language {
                type
                label
                value
            }
        }

        ... on BubblePublicationFields {
            summary {
                type
                label
                value
            }
            authors {
                type
                label
                value
            }
            collection {
                type
                label
                value
            }
            categories {
                type
                label
                value
            }

            publisher {
                type
                label
                value
            }
            date {
                type
                label
                value

                ... on BubbleDateField {
                    date
                }
            }
            language {
                type
                label
                value
            }
        }

        ... on BubbleEventFields {
            venue {
                type
                label
                value

                ... on BubbleLocationField {
                    position {
                        latitude
                        longitude
                    }
                    city
                    region
                }
            }
            room {
                type
                label
                value
            }
            category {
                type
                label
                value
            }
            date {
                type
                label
                value

                ... on BubbleDaterangeField {
                    daterange {
                        start
                        end
                    }
                }
            }
        }

        ... on BubbleBanqPhotoFields {
            date {
                type
                label
                value

                ... on BubbleDateField {
                    date
                }
            }
            publisher {
                type
                label
                value
            }
            authors {
                type
                label
                value
            }
            subjects {
                type
                label
                value
                ... on BubbleStringsField {
                    values
                }
            }
        }

        ... on BubbleBanqBookFields {
            date {
                type
                label
                value

                ... on BubbleDateField {
                    date
                }
            }
            publisher {
                type
                label
                value
            }
            authors {
                type
                label
                value
            }
            subjects {
                type
                label
                value
                ... on BubbleStringsField {
                    values
                }
            }
            characters {
                type
                label
                value
                ... on BubbleStringsField {
                    values
                }
            }
            genres {
                type
                label
                value
                ... on BubbleStringsField {
                    values
                }
            }
            locations {
                type
                label
                value
                ... on BubbleStringsField {
                    values
                }
            }
            awards {
                type
                label
                value
                ... on BubbleStringsField {
                    values
                }
            }
        }

        ... on BubbleBanqCardFields {
            date {
                type
                label
                value

                ... on BubbleDateField {
                    date
                }
            }
            publisher {
                type
                label
                value
            }
            authors {
                type
                label
                value
            }
            subjects {
                type
                label
                value
                ... on BubbleStringsField {
                    values
                }
            }
            location {
                type
                label
                value

                ... on BubbleLocationField {
                    position {
                        latitude
                        longitude
                    }
                    city
                    region
                }
            }
        }

        ... on BubbleBanqQuestionFields {
            question {
                type
                label
                value
            }
            category {
                type
                label
                value
            }
            subcategory {
                type
                label
                value
            }
            answers {
                type
                label
                value
                ... on BubbleQuizzAnswersField {
                    answers {
                    	text
                    	explanation
                    	good
                    }
                }
            }
        }

        ... on BubbleQuizzQuestionFields {
            question {
                type
                label
                value
            }
            category {
                type
                label
                value
            }
            subcategory {
                type
                label
                value
            }
            answers {
                type
                label
                value
                ... on BubbleQuizzAnswersField {
                    answers {
                    	text
                    	explanation
                    	good
                    }
                }
            }
        }

        ... on BubbleBanqServiceFields {
            title {
                type
                label
                value
            }
            description {
                type
                label
                value
            }
            service {
                type
                label
                value
            }
            credits {
                type
                label
                value
            }
        }

        ... on BubbleServiceFields {
            title {
                type
                label
                value
            }
            description {
                type
                label
                value
            }
            service {
                type
                label
                value
            }
            credits {
                type
                label
                value
            }
        }

        ... on BubbleAnnouncementFields {
            title {
                type
                label
                value
            }
            link {
                type
                label
                value
            }
            description {
                type
                label
                value
            }
        }

        ... on BubbleLocationFields {
            name {
                type
                label
                value
            }
            link {
                type
                label
                value
            }

            location {
                type
                label
                value

                ... on BubbleLocationField {
                    position {
                        latitude
                        longitude
                    }
                    city
                    region
                    address
                    postalcode
                }
            }

            phone {
                type
                label
                value
            }

            email {
                type
                label
                value
            }
        }
    }

    suggestions
}
