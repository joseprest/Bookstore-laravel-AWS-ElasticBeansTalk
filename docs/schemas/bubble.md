Bubble schema
------------------------

```
fragment bubbleFragment on Bubble {
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
    }
    filters {
        ... on BubbleBookFilters {
            author
            collection
            date
        }

        ... on BubbleEventFilters {
            group
            category
            venue
            date
        }
    }
    fields {
        ... on BubbleBookFields {
            author {
                type
                label
                value
            }
            category {
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
                }
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
                    date {
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
            }
            publisher {
                type
                label
                value
            }
            author {
                type
                label
                value
            }
        }

        ... on BubbleBanqCardFields {
            date {
                type
                label
                value
            }
            publisher {
                type
                label
                value
            }
            author {
                type
                label
                value
            }
        }
    }
}
```


```
fragment bubbleFragment on Bubble {
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
    }
    
    filtersArray {
        name
        value
    }
    
    fieldsArray {
        
        name
        type
        label
        value
        
        ... on BubbleLocationField {
            position {
                latitude
                longitude
            }
        }
        
        ... on BubbleDaterangeField {
            date {
                start
                end
            }
        }
    }
}
```
