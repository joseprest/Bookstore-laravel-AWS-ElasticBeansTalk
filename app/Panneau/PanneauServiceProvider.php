<?php namespace Manivelle\Panneau;

use Panneau\Support\ServiceProvider;

class PanneauServiceProvider extends ServiceProvider
{

    protected $fields = [
        'string' => \Manivelle\Panneau\Fields\StringField::class,
        'strings' => \Manivelle\Panneau\Fields\StringsField::class,
        'integer' => \Manivelle\Panneau\Fields\IntegerField::class,
        'toggle' => \Manivelle\Panneau\Fields\ToggleField::class,
        'position' => \Manivelle\Panneau\Fields\PositionField::class,
        'location' => \Manivelle\Panneau\Fields\LocationField::class,
        'role' => \Manivelle\Panneau\Fields\RoleField::class,
        'author' => \Manivelle\Panneau\Fields\AuthorField::class,
        'authors' => \Manivelle\Panneau\Fields\AuthorsField::class,
        'color' => \Manivelle\Panneau\Fields\ColorField::class,
        'weekdays' => \Manivelle\Panneau\Fields\WeekdaysField::class,
        'weekday' => \Manivelle\Panneau\Fields\WeekdayField::class,
        'filters' => \Manivelle\Panneau\Fields\FiltersField::class,
        'condition_weekdays' => \Manivelle\Panneau\Fields\ConditionWeekdaysField::class,
        'condition_daterange' => \Manivelle\Panneau\Fields\ConditionDateRangeField::class,
        'condition_date' => \Manivelle\Panneau\Fields\ConditionDateField::class,
        'condition_time' => \Manivelle\Panneau\Fields\ConditionTimeField::class,
        'screen_technical' => \Manivelle\Panneau\Fields\ScreenTechnicalField::class,
        'screen_resolution' => \Manivelle\Panneau\Fields\ScreenResolutionField::class,
        'channel_theme' => \Manivelle\Panneau\Fields\ChannelThemeField::class,
        'channel_settings' => \Manivelle\Panneau\Fields\ChannelSettingsField::class,

        'category' => \Manivelle\Panneau\Fields\CategoryField::class,
        'categories' => \Manivelle\Panneau\Fields\CategoriesField::class,

        'person' => \Manivelle\Panneau\Fields\PersonField::class,
        'persons' => \Manivelle\Panneau\Fields\PersonsField::class,
    ];

    protected $resources = [
        'channels' => \Manivelle\Panneau\Resources\ChannelsResource::class,
        'playlists' => \Manivelle\Panneau\Resources\PlaylistsResource::class,
        'screens' => \Manivelle\Panneau\Resources\ScreensResource::class,
        'organisations' => \Manivelle\Panneau\Resources\OrganisationsResource::class,
        'bubbles' => \Manivelle\Panneau\Resources\BubblesResource::class,
        'conditions' => \Manivelle\Panneau\Resources\ConditionsResource::class,
        'sources' => \Manivelle\Panneau\Resources\SourcesResource::class
    ];

    protected $forms = [
        'auth.reset_email' => 'Manivelle\Panneau\Form\AuthResetEmailForm',
        'auth.reset' => 'Manivelle\Panneau\Form\AuthResetForm',

        'channel' => 'Manivelle\Panneau\Form\ChannelForm',
        'channel.settings' => 'Manivelle\Panneau\Form\ChannelSettingsForm',
        'channel.filters' => 'Manivelle\Panneau\Form\ChannelFiltersForm',
        'bubble' => 'Manivelle\Panneau\Form\BubbleForm',

        'screen' => 'Manivelle\Panneau\Form\ScreenForm',
        'screen.link' => 'Manivelle\Panneau\Form\ScreenLinkForm',
        'screen.create' => 'Manivelle\Panneau\Form\ScreenCreateForm',
        'screen.unlink' => 'Manivelle\Panneau\Form\ScreenUnlinkForm',
        'screen.settings' => 'Manivelle\Panneau\Form\ScreenSettingsForm',

        'account' => 'Manivelle\Panneau\Form\AccountForm',
        'account.delete' => 'Manivelle\Panneau\Form\AccountDeleteForm',

        'team' => 'Manivelle\Panneau\Form\TeamForm',
        'team.invite' => 'Manivelle\Panneau\Form\TeamInviteForm',
        'team.edit' => 'Manivelle\Panneau\Form\TeamEditForm',
        'team.invitation' => 'Manivelle\Panneau\Form\TeamInvitationForm',

        'invitation.register' => 'Manivelle\Panneau\Form\InvitationRegisterForm',

        'organisation' => 'Manivelle\Panneau\Form\OrganisationForm'
    ];

    protected $itemsLists = [
        'admin.organisations' => 'Manivelle\Panneau\ItemsList\AdminOrganisationsList',

        'organisations' => 'Manivelle\Panneau\ItemsList\OrganisationsList',
        'organisation.screens' => 'Manivelle\Panneau\ItemsList\OrganisationScreensList',
        'organisation.team' =>   'Manivelle\Panneau\ItemsList\OrganisationTeamList',

        'channels' => 'Manivelle\Panneau\ItemsList\ChannelsList',

        'playlist' => 'Manivelle\Panneau\ItemsList\PlaylistList',

        'bubbles' => 'Manivelle\Panneau\ItemsList\Bubbles\BubblesList',
        'bubbles.playlist' => 'Manivelle\Panneau\ItemsList\Bubbles\PlaylistList',
        'bubbles.channel' => 'Manivelle\Panneau\ItemsList\Bubbles\ChannelList',
        'bubbles.channel.filters' => 'Manivelle\Panneau\ItemsList\Bubbles\ChannelFiltersList',
        'bubbles.timeline' => 'Manivelle\Panneau\ItemsList\Bubbles\TimelineList'
    ];
}
