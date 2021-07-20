<?php

return [

    'prefix' => null,

    'routes' => null,

    'controllers' => '\Folklore\GraphQL\GraphQLController@query',

    'schema' => [
        'query' => [
            'organisation' => \Manivelle\GraphQL\Query\Organisation::class,
            'organisations' => \Manivelle\GraphQL\Query\Organisations::class,
            'bubbles' => \Manivelle\GraphQL\Query\Bubbles::class,
            'bubblesPaginated' => \Manivelle\GraphQL\Query\BubblesPaginated::class,
            'bubblesIdsPaginated' => \Manivelle\GraphQL\Query\BubblesIdsPaginated::class,
            'bubblesIds' => \Manivelle\GraphQL\Query\BubblesIds::class,
            'playlists' => \Manivelle\GraphQL\Query\Playlists::class,
            'playlistItems' => \Manivelle\GraphQL\Query\PlaylistItems::class,
            'channels' => \Manivelle\GraphQL\Query\Channels::class,
            'screens' => \Manivelle\GraphQL\Query\Screens::class,
            'conditions' => \Manivelle\GraphQL\Query\Conditions::class,
            'timeline' => \Manivelle\GraphQL\Query\Timeline::class,
            'channelFilterTokens' => \Manivelle\GraphQL\Query\ChannelFilterTokens::class,
            'channelFilterValues' => \Manivelle\GraphQL\Query\ChannelFilterValues::class
        ],

        'mutation' => [
            'screenAddChannel' => \Manivelle\GraphQL\Mutation\ScreenAddChannel::class,
            'screenRemoveChannel' => \Manivelle\GraphQL\Mutation\ScreenRemoveChannel::class,
            'screenSaveChannelSettings' => \Manivelle\GraphQL\Mutation\ScreenSaveChannelSettings::class,
            'screenSendCommand' => \Manivelle\GraphQL\Mutation\ScreenSendCommand::class,
            'screenAttachPlaylist' => \Manivelle\GraphQL\Mutation\ScreenAttachPlaylist::class,
            'screenDetachPlaylist' => \Manivelle\GraphQL\Mutation\ScreenDetachPlaylist::class,

            'saveCondition' => \Manivelle\GraphQL\Mutation\SaveCondition::class,
            'playlistCreate' => \Manivelle\GraphQL\Mutation\PlaylistCreate::class,
            'playlistRename' => \Manivelle\GraphQL\Mutation\PlaylistRename::class,
            'playlistDelete' => \Manivelle\GraphQL\Mutation\PlaylistDelete::class,
            'playlistAddBubble' => \Manivelle\GraphQL\Mutation\PlaylistAddBubble::class,
            'playlistAddBubbles' => \Manivelle\GraphQL\Mutation\PlaylistAddBubbles::class,
            'playlistAddFilters' => \Manivelle\GraphQL\Mutation\PlaylistAddFilters::class,
            'playlistRemoveBubble' => \Manivelle\GraphQL\Mutation\PlaylistRemoveBubble::class,
            'playlistUpdateOrder' => \Manivelle\GraphQL\Mutation\PlaylistUpdateOrder::class,

            'userUpdateAvatar' => \Manivelle\GraphQL\Mutation\UserUpdateAvatar::class,
            'userRemoveAvatar' => \Manivelle\GraphQL\Mutation\UserRemoveAvatar::class,

            'organisationLinkScreen' => \Manivelle\GraphQL\Mutation\OrganisationLinkScreen::class,
            'organisationCreateScreen' => \Manivelle\GraphQL\Mutation\OrganisationCreateScreen::class,
            'organisationInviteUser' => \Manivelle\GraphQL\Mutation\OrganisationInviteUser::class,
            'organisationRemoveUser' => \Manivelle\GraphQL\Mutation\OrganisationRemoveUser::class,
            'organisationRemoveInvitation' => \Manivelle\GraphQL\Mutation\OrganisationRemoveInvitation::class,
            'organisationUpdateUser' => \Manivelle\GraphQL\Mutation\OrganisationUpdateUser::class,
            'organisationUpdateInvitation' => \Manivelle\GraphQL\Mutation\OrganisationUpdateInvitation::class,

            'channelRemoveBubble' => \Manivelle\GraphQL\Mutation\ChannelRemoveBubble::class
        ]
    ],

    'types' => [

        'Bubble' => \Manivelle\GraphQL\Type\BubbleType::class,
        'BubblesPaginated' => \Manivelle\GraphQL\Type\BubblesPaginatedType::class,
        'BubblesIdsPaginated' => \Manivelle\GraphQL\Type\BubblesIdsPaginatedType::class,
        'BubblesIds' => \Manivelle\GraphQL\Type\BubblesIdsType::class,

        'Channel' => \Manivelle\GraphQL\Type\ChannelType::class,
        'Screen' => \Manivelle\GraphQL\Type\ScreenType::class,
        'ScreenChannelSettings' => \Manivelle\GraphQL\Type\ScreenChannelSettingsType::class,
        'ScreenCommand' => \Manivelle\GraphQL\Type\ScreenCommandType::class,

        'Picture' => \Manivelle\GraphQL\Type\PictureType::class,
        'Organisation' => \Manivelle\GraphQL\Type\OrganisationType::class,
        'User' => \Manivelle\GraphQL\Type\UserType::class,
        'Role' => \Manivelle\GraphQL\Type\RoleType::class,
        'OrganisationInvitation' => \Manivelle\GraphQL\Type\OrganisationInvitationType::class,

        'Playlist' => \Manivelle\GraphQL\Type\PlaylistType::class,
        'PlaylistItem' => \Manivelle\GraphQL\Type\PlaylistItemType::class,

        'Pagination' => \Manivelle\GraphQL\Type\PaginationType::class,
        'Snippet' => \Manivelle\GraphQL\Type\SnippetType::class,
        'Filter' => \Manivelle\GraphQL\Type\FilterType::class,
        'SelectValue' => \Manivelle\GraphQL\Type\SelectValueType::class,
        'Position' => \Manivelle\GraphQL\Type\PositionType::class,
        'Daterange' => \Manivelle\GraphQL\Type\DaterangeType::class,

        'Timeline' => \Manivelle\GraphQL\Type\TimelineType::class,
        'TimelineCycle' => \Manivelle\GraphQL\Type\TimelineCycleType::class,
        'TimelineItem' => \Manivelle\GraphQL\Type\TimelineItemType::class,

        'Condition' => \Manivelle\GraphQL\Type\ConditionType::class,
        'ConditionFields' => \Manivelle\GraphQL\Type\ConditionFieldsType::class,

        'ChannelSettings' => \Manivelle\GraphQL\Type\ChannelSettingsType::class,
        'ChannelSettingsBubbleDetailsContentColumn' => \Manivelle\GraphQL\Type\ChannelSettings\BubbleDetailsContentColumnType::class,

        'ChannelTheme' => \Manivelle\GraphQL\Type\ChannelThemeType::class,

        'ChannelFieldsInterface' => \Manivelle\GraphQL\Type\ChannelFieldsInterface::class,
        'ChannelFields' => \Manivelle\GraphQL\Type\ChannelFieldsType::class,
        'ChannelField' => \Manivelle\GraphQL\Type\ChannelFieldType::class,

        'ChannelFilterToken' => \Manivelle\GraphQL\Type\ChannelFilterToken::class,
        'ChannelFilterInterface' => \Manivelle\GraphQL\Type\ChannelFilter\ChannelFilterInterface::class,
        'ChannelFilter' => \Manivelle\GraphQL\Type\ChannelFilter\ChannelFilterType::class,
        'ChannelFilterList' => \Manivelle\GraphQL\Type\ChannelFilter\ChannelFilterListType::class,
        'ChannelFilterMap' => \Manivelle\GraphQL\Type\ChannelFilter\ChannelFilterMapType::class,
        'ChannelFilterValueInterface' => \Manivelle\GraphQL\Type\ChannelFilterValue\ChannelFilterValueInterface::class,
        'ChannelFilterValue' => \Manivelle\GraphQL\Type\ChannelFilterValue\ChannelFilterValueType::class,
        'ChannelFilterValueListAlphabetic' => \Manivelle\GraphQL\Type\ChannelFilterValue\ChannelFilterValueListAlphabeticType::class,
        'ChannelFilterValueListCalendar' => \Manivelle\GraphQL\Type\ChannelFilterValue\ChannelFilterValueListCalendarType::class,
        'ChannelFilterValueMap' => \Manivelle\GraphQL\Type\ChannelFilterValue\ChannelFilterValueMapType::class,

        'ChannelBubbleFilterInterface' => \Manivelle\GraphQL\Type\ChannelBubbleFilter\ChannelBubbleFilterInterface::class,
        'ChannelBubbleFilter' => \Manivelle\GraphQL\Type\ChannelBubbleFilter\ChannelBubbleFilterType::class,
        'ChannelBubbleFilterSelect' => \Manivelle\GraphQL\Type\ChannelBubbleFilter\ChannelBubbleFilterSelectType::class,

        'BubbleFieldsInterface' => \Manivelle\GraphQL\Type\BubbleFieldsInterface::class,
        'BubbleFields' => \Manivelle\GraphQL\Type\BubbleFieldsType::class,
        'BubbleFieldInterface' => \Manivelle\GraphQL\Type\BubbleFieldInterface::class,
        'BubbleField' => \Manivelle\GraphQL\Type\BubbleField\BubbleFieldType::class,
        'BubbleDaterangeField' => \Manivelle\GraphQL\Type\BubbleField\BubbleDaterangeFieldType::class,
        'BubbleDateField' => \Manivelle\GraphQL\Type\BubbleField\BubbleDateFieldType::class,
        'BubbleLocationField' => \Manivelle\GraphQL\Type\BubbleField\BubbleLocationFieldType::class,
        'BubbleStringsField' => \Manivelle\GraphQL\Type\BubbleField\BubbleStringsFieldType::class,
        'BubbleCategoryField' => \Manivelle\GraphQL\Type\BubbleField\BubbleCategoryFieldType::class,

        'BubbleFiltersInterface' => \Manivelle\GraphQL\Type\BubbleFiltersInterface::class,
        'BubbleFilters' => \Manivelle\GraphQL\Type\BubbleFiltersType::class
    ]



];
