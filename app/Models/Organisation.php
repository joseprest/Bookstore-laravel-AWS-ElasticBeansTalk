<?php namespace Manivelle\Models;

use Manivelle\User;
use Manivelle\Models\Role;
use Manivelle\Events\OrganisationUserInvited;
use Manivelle\Events\ScreenLinked;

use DB;
use Event;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Folklore\EloquentMediatheque\Traits\LinkableTrait;

use Manivelle\Models\Traits\LoadTrait;
use Localizer;

class Organisation extends Model implements SluggableInterface
{
    use LoadTrait, SluggableTrait, LinkableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'organisations';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'address',
        'city',
        'country',
        'region',
        'postalcode',
        'locale',
        'settings',
        'email_from',
        'email_subject'
    ];

    protected $hidden = [
        'screens',
        'users'
    ];

    protected $appends = [
        'link',
        'email_from',
        'email_subject'
    ];

    protected $casts = [
        'settings' => 'object'
    ];

    protected $sluggable = [
        'build_from' => 'name',
        'separator' => ''
    ];

    /**
     * Relationships
     */
    public function screens()
    {
        return $this->hasMany(\Manivelle\Models\OrganisationScreen::class, 'organisation_id');
    }

    public function users()
    {
        return $this->hasMany(\Manivelle\Models\OrganisationUser::class, 'organisation_id');
    }

    public function channels()
    {
        return $this->hasMany(\Manivelle\Models\OrganisationChannel::class, 'organisation_id');
    }

    public function playlists()
    {
        return $this->hasMany('Manivelle\Models\Playlist', 'organisation_id');
    }

    public function playlistScreens()
    {
        return $this->belongsToMany(
            \Manivelle\Models\Playlist::class,
            'screens_playlists_pivot',
            'organisation_id',
            'playlist_id'
        )
        ->withPivot('id', 'screen_id', 'playlist_id', 'organisation_id', 'settings', 'condition_id')
        ->withTimestamps();
    }

    public function invitations()
    {
        return $this->hasMany('Manivelle\Models\OrganisationInvitation', 'organisation_id');
    }

    /**
     * Load the relations to access organisation team
     *
     * @return void
     */
    public function loadTeam()
    {
        $this->loadUsers();
        $this->loadInvitations();
    }

    /**
     * Load the relations to access organisation users
     *
     * @return void
     */
    public function loadUsers()
    {
        $this->loadIfNotLoaded([
            'users',
            'users.user',
            'users.user.pictures',
            'users.role'
        ]);
    }

    /**
     * Load the relations to access organisation invitations
     *
     * @return void
     */
    public function loadInvitations()
    {
        $this->loadIfNotLoaded([
            'invitations',
            'invitations.user',
            'invitations.user.pictures',
            'invitations.role'
        ]);
    }

    /**
     * Load the relations to access organisation screens
     *
     * @return void
     */
    public function loadScreens()
    {
        $this->loadIfNotLoaded([
            'screens',
            'screens.screen.metadatas',
            'screens.screen.texts',
            'screens.screen.pictures',
            'screens.screen.channels',
            'screens.screen.channels.metadatas',
            'screens.screen.channels.texts',
            'screens.screen.channels.pictures',
            'screens.screen.playlists',
            'screens.screen.organisations'
        ]);
    }

    /**
     * Attach a user and a role to the organisation
     *
     * @param  Manivelle\User $user The user to attach
     * @param  Manivelle\Models\Role $role The role of the user
     * @return Manivelle\Models\OrganisationUser The organisation user
     */
    public function attachUser(User $user, Role $role)
    {
        $table = with(new OrganisationUser())->getTable();
        $query = $this->users()->where($table.'.user_id', $user->id);
        $current = $query->first();
        if ($current) {
            $current->role()->associate($role);
        } else {
            $organisationUser = new OrganisationUser();
            $organisationUser->user_id = $user->id;
            $organisationUser->user_role_id = $role->id;
            $organisationUser->save();
            $this->users()->save($organisationUser);
        }

        return $query->first();
    }

    /**
     * Invite an email to the organisation. The email could be one from an
     * already registered user, or not.
     *
     * @param  string $email The email to invite
     * @param  Manivelle\Models\Role $role The role of the user
     * @return Manivelle\Models\OrganisationInvitation The organisation invitation
     */
    public function inviteUser($email, Role $role)
    {
        $user = User::where('email', 'LIKE', strtolower($email))->first();

        // Get current invitation
        $query = $this->invitations();
        $query->where(function ($query) use ($user, $email) {
            if ($user) {
                $query->where('user_id', $user->id);
                $query->orWhere('email', 'LIKE', strtolower($email));
            } else {
                $query->where('email', 'LIKE', strtolower($email));
            }
        });
        $current = $query->first();

        // If not found, create a new invitation
        if (!$current) {
            $current = new OrganisationInvitation();
        }

        // Update invitation
        if ($user) {
            $current->user_id = $user->id;
            $current->email = $user->email;
        } else {
            $current->user_id = 0;
            $current->email = $email;
        }
        $current->user_role_id = $role->id;

        $this->invitations()->save($current);

        $dispatcher = self::getEventDispatcher();
        if ($dispatcher) {
            $dispatcher->fire(new OrganisationUserInvited($current));
        }

        return $query->first();
    }

    /**
     * Update the role of an organisation user
     *
     * @param  Manivelle\User $user The user to attach
     * @param  Manivelle\Models\Role $role The new role of the user
     * @return Manivelle\Models\OrganisationUser The organisation user
     */
    public function updateUser(User $user, Role $role)
    {
        $table = with(new OrganisationUser())->getTable();
        $query = $this->users()->where($table.'.user_id', $user->id)
                                ->where($table.'.organisation_id', $this->id);
        $current = $query->first();

        if ($current) {
            $current->user_role_id = $role->id;
            $current->save();
        }

        return $query->with('role')->first();
    }

    /**
     * Update the role of an invitation
     *
     * @param  Manivelle\Models\OrganisationInvitation $invitation The invitation
     * @param  Manivelle\Models\Role $role The new role of the invitation
     * @return Manivelle\Models\OrganisationInvitation The organisation invitation
     */
    public function updateInvitation(OrganisationInvitation $invitation, Role $role)
    {
        $invitation->user_role_id = $role->id;
        $invitation->save();

        return $invitation;
    }

    /**
     * Remove a user from the organisation
     *
     * @param  Manivelle\User $user The user
     * @return Manivelle\Models\OrganisationUser The organisation user
     */
    public function removeUser(User $user)
    {
        $table = with(new OrganisationUser())->getTable();
        $this->users()->where($table.'.user_id', $user->id)
                    ->where($table.'.organisation_id', $this->id)
                    ->delete();

        return $user;
    }

    /**
     * Remove an invitation
     *
     * @param  Manivelle\Models\OrganisationInvitation $invitation The invitation
     * @return Manivelle\Models\OrganisationInvitation The organisation invitation
     */
    public function removeInvitation(OrganisationInvitation $invitation)
    {
        $invitation->delete();

        return $invitation;
    }

    /**
     * Get role of a user
     *
     * @param  Manivelle\Models\OrganisationInvitation $invitation The invitation
     * @return Manivelle\Models\OrganisationInvitation The organisation invitation
     */
    public function getUserRole(User $user)
    {
        $this->loadIfNotLoaded([
            'users',
            'users.user',
            'users.role'
        ]);

        $organisationUser = $this->users->first(function ($key, $organisationUser) use ($user) {
            return (int)$organisationUser->user_id === (int)$user->id;
        });

        return $organisationUser ? $organisationUser->role:null;
    }

    /**
     * Link a screen to this organisation. It attach the screen to the organisation
     * and also create the default playlists and add default channels.
     *
     * @param  Manivelle\Models\Screen $screen The screen to attach
     * @return Manivelle\Models\OrganisationScreen The organisation screen
     */
    public function linkScreen(Screen $screen)
    {
        $screen = $this->attachScreen($screen);

        $screen->createPlaylistsForOrganisation($this);
        $screen->addDefaultChannels($this);

        $screen = Screen::find($screen->id);

        $dispatcher = self::getEventDispatcher();
        if ($dispatcher) {
            $dispatcher->fire(new ScreenLinked($screen));
        }

        return $screen;
    }

    /**
     * Unlink a screen from the organisation
     *
     * @param  Manivelle\Models\Screen $screen The screen to attach
     * @return Manivelle\Models\OrganisationScreen The organisation screen
     */
    public function unlinkScreen(Screen $screen)
    {
        $screen->removeOrganisationChannels($this);
        $screen->removeOrganisationPlaylists($this);
        $this->detachScreen($screen);
        $screen = Screen::find($screen->id);

        $dispatcher = self::getEventDispatcher();
        if ($dispatcher) {
            $dispatcher->fire(new ScreenLinked($screen));
        }

        return $screen;
    }

    /**
     * Attach a screen to the organisation
     *
     * @param  Manivelle\Models\Screen $screen The screen to attach
     * @return Manivelle\Models\OrganisationScreen The organisation screen
     */
    public function attachScreen(Screen $screen)
    {
        $organisationScreen = new OrganisationScreen();
        $table = $organisationScreen->getTable();

        $query = $this->screens()->where($table.'.screen_id', $screen->id);
        $current = $query->first();
        if ($current) {
            return $current;
        }

        $organisationScreen->screen_id = $screen->id;
        $this->screens()->save($organisationScreen);
        return $query->first();
    }

    /**
     * Detach a screen from the organisation
     *
     * @param  Manivelle\Models\Screen|Manivelle\Models\OrganisationScreen $screen The screen to attach
     * @return Manivelle\Models\OrganisationScreen The organisation screen
     */
    public function detachScreen($screen)
    {
        $table = with(new OrganisationScreen())->getTable();
        $id = $screen instanceof Screen ? $screen->id:$screen->screen_id;
        $current = $this->screens()->where($table.'.screen_id', $id)->first();
        if ($current) {
            $current->delete();
        }

        return $current;
    }

    /**
     * Get the link of the organisation
     *
     * @return string The link of the organisation
     */
    public function getLink()
    {
        return route(Localizer::routeName('organisation.home'), array($this->slug));
    }

    /**
     * Get the team of the organisation. It returns a collection with both
     * invitations and users.
     *
     * @return Illuminate\Support\Collection The team of the organisation
     */
    public function getTeam()
    {
        $team = new Collection();

        foreach ($this->users as $user) {
            $user->type = 'user';
            $team->push($user);
        }

        foreach ($this->invitations as $invitation) {
            $invitation->type = 'invitation';
            $team->push($invitation);
        }

        return $team;
    }

    protected function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
    }

    protected function getScreensIdAttribute()
    {
        $ids = array();
        foreach ($this->screens as $screen) {
            $ids[] = $screen->id;
        }
        return $ids;
    }

    protected function getEmailFromAttribute()
    {
        $from = config('mail.from');
        if (isset($this->settings->email_from) && !empty($this->settings->email_from)) {
            array_set($from, 'address', $this->settings->email_from);
        }
        if (isset($this->settings->email_from_name) && !empty($this->settings->email_from_name)) {
            array_set($from, 'name', $this->settings->email_from_name);
        } else {
            array_set($from, 'name', $this->name);
        }
        return $from;
    }

    protected function getEmailSubjectAttribute()
    {
        $subject = trans('share.email.subject');
        if (isset($this->settings->email_subject) && !empty($this->settings->email_subject)) {
            $subject = $this->settings->email_subject;
        }
        return $subject;
    }
}
