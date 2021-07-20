Channel schema
------------------------

```
fragment channelFragment on Channel {
    id
    type
    snippet {
        title
        subtitle
        description
        summary
        picture {
            link
        }
    }
    
    fields {
    
        theme {
            color_light
            color_medium
            color_normal
            color_dark
            color_darker
            color_shadow
            color_shadow_darker
        }
        
        settings {
            channelView

			slidesHeightRatio
			slidesMarginRatio
			slidesWidthRatio
			slidesSlideView

			bubbleDetailsShowTypeName
			bubbleDetailsShowTitle
			bubbleDetailsContentView
			bubbleDetailsContentColumns {
				column
				type
				value
			}
        }
    }
    
    filters {
        name
        type
        label
        
        ... on ChannelFilterList {
            layout
        }
        
        values {
            value
            label
            
            ... on ChannelFilterValueListAlphabetic {
                alpha
            }
            
            ... on ChannelFilterValueListCalendar {
                date
            }
            
            ... on ChannelFilterValueMap {
                position {
                    latitude
                    longitude
                }
            }
        }
    }
    
    bubbles_ids
}
```
