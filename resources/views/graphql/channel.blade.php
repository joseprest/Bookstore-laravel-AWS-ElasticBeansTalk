fragment channelFields on Channel {
    id
	type
	snippet {
		title
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
            channelMarkerType
			colorPalette
            randomPositionCards
            channelFilterName

			slideshowInfosView
            slideshowImageMaxWidth

			slidesHeightRatio
			slidesMarginRatio
			slidesWidthRatio
			slidesSlideView
            slideMenuDestinationView

			modalBubblesListView
            modalSendBubbleHasMessage
            modalSendBubbleDefaultMessage

			bubbleDetailsShowTypeName
			bubbleDetailsShowTitle
			bubbleDetailsContentView
			bubbleDetailsExcludedButtons
			bubbleDetailsContentColumns {
				column
				type
				value
			}

			bubbleSuggestionView
		}
	}

	filters {
		name
		type
		label

		... on ChannelFilterList {
			layout
		}

        ... on ChannelFilterMap {
            markerType
            clusterIconType
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
                city
                region
			}
		}
	}

	bubbles_ids
}
