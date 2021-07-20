<?php namespace Manivelle\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;

class ViewsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['layouts.main', 'layouts.organisation'], 'Manivelle\View\Composers\Layouts\LayoutComposer');
        view()->composer(['layouts.main', 'layouts.organisation'], 'Panneau\View\Composers\LayoutComposer');
        view()->composer('layouts.screen', 'Manivelle\View\Composers\Layouts\ScreenComposer');

        view()->composer('partials.header', 'Manivelle\View\Composers\HeaderComposer');
        view()->composer('partials.header_user', 'Manivelle\View\Composers\HeaderUserComposer');
        view()->composer('partials.header_locale', 'Manivelle\View\Composers\HeaderLocaleComposer');
        view()->composer('partials.footer_assets', 'Manivelle\View\Composers\FooterAssetsComposer');

        view()->composer('admin.submenu', 'Manivelle\View\Composers\Admin\SubmenuComposer');

        view()->composer('home.public', 'Manivelle\View\Composers\Home\PublicComposer');

        view()->composer('auth.passwords.email', 'Manivelle\View\Composers\Auth\PasswordsEmailComposer');
        view()->composer('auth.passwords.reset', 'Manivelle\View\Composers\Auth\PasswordsResetComposer');

        view()->composer('account.index', 'Manivelle\View\Composers\Account\IndexComposer');

        view()->composer('screen.index', 'Manivelle\View\Composers\Screen\ScreenComposer');

        view()->composer('organisation.index', 'Manivelle\View\Composers\Organisation\IndexComposer');
        view()->composer('organisation.edit', 'Manivelle\View\Composers\Organisation\EditComposer');
        view()->composer('organisation.team.show', 'Manivelle\View\Composers\Organisation\Team\ShowComposer');
        view()->composer('organisation.invitation.register', 'Manivelle\View\Composers\Organisation\Invitation\RegisterComposer');
        view()->composer('organisation.invitation.link', 'Manivelle\View\Composers\Organisation\Invitation\LinkComposer');
        view()->composer('organisation.screens.show', 'Manivelle\View\Composers\Organisation\Screens\ShowComposer');
        view()->composer('organisation.screens.channel', 'Manivelle\View\Composers\Organisation\Screens\ChannelComposer');
        view()->composer('organisation.screens.tabs.slideshow', 'Manivelle\View\Composers\Organisation\Screens\Tabs\SlideshowComposer');
        view()->composer('organisation.screens.tabs.notifications', 'Manivelle\View\Composers\Organisation\Screens\Tabs\NotificationsComposer');
        view()->composer('organisation.screens.tabs.channels', 'Manivelle\View\Composers\Organisation\Screens\Tabs\ChannelsComposer');
        view()->composer('organisation.screens.tabs.settings', 'Manivelle\View\Composers\Organisation\Screens\Tabs\SettingsComposer');
        view()->composer('organisation.screens.tabs.controls', 'Manivelle\View\Composers\Organisation\Screens\Tabs\ControlsComposer');
        view()->composer('organisation.screens.tabs.stats', 'Manivelle\View\Composers\Organisation\Screens\Tabs\StatsComposer');

        //Emails
        view()->composer('emails.share_message', 'Manivelle\View\Composers\Emails\ShareMessageComposer');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
