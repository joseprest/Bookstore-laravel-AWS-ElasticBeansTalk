<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use DB;
use Log;
use Ramsey\Uuid\Uuid;
use Cache;
use Event;
use Manivelle;
use Panneau;
use Localizer;
use Illuminate\Support\Collection;

use Panneau\Bubbles\Models\Bubble as BaseBubble;
use Manivelle\Models\Channel;
use Manivelle\Models\Bubble;
use Manivelle\Models\Condition;
use Manivelle\Models\Playlist;

use Manivelle\Models\Traits\LoadTrait;
use Manivelle\Models\Traits\HasFieldLocations;

use Manivelle\Events\ScreenChanged;
use Manivelle\Events\ScreenChannelAdded;
use Manivelle\Events\ScreenChannelRemoved;
use Manivelle\Events\ScreenPlaylistAttached;
use Manivelle\Events\ScreenPlaylistDetached;

class Screen extends BaseBubble
{
    use LoadTrait, HasFieldLocations;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'screens';

    protected $fillable = ['name', 'slug', 'uuid', 'settings'];

    protected $visible = [
        'id',
        'slug',
        'auth_code',
        'uuid',
        'name',
        'fields',
        'snippet',
        'online',
        'linked',
        'url',
        'settings',
    ];

    protected $appends = ['fields', 'snippet', 'online', 'linked', 'url'];

    protected $with = ['last_ping'];

    protected $casts = [
        'settings' => 'array',
    ];

    protected $screenType;

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        // Create an auth code and uuid if not present.
        static::creating(function ($model) {
            if (empty($model->auth_code)) {
                $model->auth_code = self::createAuthCode();
            }
            if (empty($model->uuid)) {
                $model->uuid = self::createUUID();
            }
        });
    }

    protected static function createUUID()
    {
        return Uuid::uuid1();
    }

    protected static function createAuthCode()
    {
        $item = DB::table('screens')
            ->select(DB::raw('FLOOR(1000 + RAND() * 8999) AS random_auth_code'))
            ->whereRaw('"random_auth_code" NOT IN (SELECT auth_code FROM screens)')
            ->first();
        return $item->random_auth_code;
    }

    /**
     * Relationships
     */
    public function channels()
    {
        return $this->hasMany(\Manivelle\Models\ScreenChannel::class, 'screen_id');
    }

    public function caches()
    {
        return $this->hasMany(\Manivelle\Models\ScreenCache::class, 'screen_id');
    }

    public function organisations()
    {
        return $this->belongsToMany(
            \Manivelle\Models\Organisation::class,
            'organisations_screens_pivot',
            'screen_id',
            'organisation_id'
        )
            ->withPivot('id', 'screen_id', 'organisation_id')
            ->withTimestamps();
    }

    public function playlists()
    {
        return $this->belongsToMany(
            \Manivelle\Models\Playlist::class,
            'screens_playlists_pivot',
            'screen_id',
            'playlist_id'
        )
            ->withPivot('id', 'screen_id', 'playlist_id', 'settings', 'condition_id')
            ->withTimestamps();
    }

    public function playlistsSlideshow()
    {
        return $this->belongsToMany(
            \Manivelle\Models\Playlist::class,
            'screens_playlists_pivot',
            'screen_id',
            'playlist_id'
        )
            ->withPivot('id', 'screen_id', 'playlist_id', 'settings', 'condition_id')
            ->where('playlists.type', 'organisation.screen.slideshow')
            ->withTimestamps();
    }

    public function pings()
    {
        return $this->hasMany(\Manivelle\Models\ScreenPing::class, 'screen_id');
    }

    public function commands()
    {
        return $this->hasMany(\Manivelle\Models\ScreenCommand::class, 'screen_id');
    }

    public function last_ping()
    {
        $join = DB::raw("
            (
                select screen_id, max(created_at) as latest
                from screens_pings
                group by screen_id
            ) as r
        ");

        return $this->hasOne(\Manivelle\Models\ScreenPing::class, 'screen_id')->join(
            $join,
            function ($join) {
                $join->on('screens_pings.created_at', '=', 'r.latest');
                $join->on('screens_pings.screen_id', '=', 'r.screen_id');
            }
        );
    }

    /**
     * Model fields
     */
    public function getScreenType()
    {
        if ($this->screenType) {
            return $this->screenType;
        }

        $this->screenType = app('manivelle')->screenType('screen');
        $this->screenType->withModel($this);

        return $this->screenType;
    }

    /**
     * Returns the default locale set in the settings. If none is defined, returns the locale in the
     * config locale.locale.
     *
     * @return String
     */
    public function getDefaultLocale()
    {
        $fallbackLocale = config('locale.locale');
        return array_get($this->settings, 'defaultLocale', $fallbackLocale);
    }

    /**
     * Returns an array of all the supported locales by this screen.
     *
     * @return Array
     */
    public function getSupportedLocales()
    {
        // For now, returns the config of locale.locales, but one day it could be specific per
        // screen.
        return Localizer::getScreensLocales();
    }

    protected function getBubbleType()
    {
        return $this->getScreenType();
    }

    /**
     * Get the commands to execute
     * @return Illuminate\Support\Collection The list of ScreenCommand
     */
    public function getCommandsToExecute()
    {
        return $this->commands()
            ->whereNull('sended_at')
            ->orderBy('created_at', 'ASC')
            ->get();
    }

    /**
     * Create default playlists for an organisation
     *
     * @param Manivelle\Models\Organisation $organisation The organisation
     * @return Illuminate\Support\Collection The collection of playlists
     */
    public function createPlaylistsForOrganisation(Organisation $organisation)
    {
        $pivot = array(
            'screen_id' => $this->id,
            'organisation_id' => $organisation->id,
        );

        $slideshowPlaylist = $this->playlists()->firstOrCreate(
            array(
                'type' => 'organisation.screen.slideshow',
                'name' => 'Playlist ' . $this->name,
            ),
            $pivot
        );

        $notificationsPlaylist = $this->playlists()->firstOrCreate(
            array(
                'type' => 'organisation.screen.notifications',
                'name' => 'Playlist ' . $this->name,
            ),
            $pivot
        );

        $playlists = with(new Playlist())->newCollection([
            $slideshowPlaylist,
            $notificationsPlaylist,
        ]);

        return $playlists;
    }

    /**
     * Add default organisation channels to a screen. If there is no default channels
     * for this organisation, we use default manivelle channels from the config.
     *
     * @param Manivelle\Models\Organisation $organisation The organisation
     * @return Illuminate\Support\Collection The collection of channels
     */
    public function addDefaultChannels(Organisation $organisation)
    {
        $channels = [];
        $settings = $organisation->settings;
        $defaultChannels = isset($settings->default_channels) ? $settings->default_channels : null;
        if (!$defaultChannels) {
            $defaultChannels = config('manivelle.screens.default_channels');
        }
        foreach ($defaultChannels as $channelHandle) {
            $channel = Channel::where('handle', $channelHandle)->first();
            if ($channel) {
                $this->attachChannel($channel, $organisation);
                $channels[] = $channel;
            }
        }

        return with(new Channel())->newCollection($channels);
    }

    /**
     * Remove channels from an organisation
     *
     * @param Manivelle\Models\Organisation $organisation The organisation
     * @return Illuminate\Support\Collection The collection of channels
     */
    public function removeOrganisationChannels(Organisation $organisation)
    {
        $screenChannel = new ScreenChannel();
        $table = $screenChannel->getTable();
        $items = $this->channels()
            ->where($table . '.organisation_id', $organisation->id)
            ->get();
        foreach ($items as $item) {
            $this->detachChannel($item->channel, $organisation);
        }

        return $items;
    }

    /**
     * Remove playlists from an organisation
     *
     * @param Manivelle\Models\Organisation $organisation The organisation
     * @return Illuminate\Support\Collection The collection of playlists
     */
    public function removeOrganisationPlaylists(Organisation $organisation)
    {
        $items = $this->playlists()
            ->where('screens_playlists_pivot.organisation_id', $organisation->id)
            ->get();
        foreach ($items as $item) {
            $this->detachPlaylist($item, $organisation);
        }

        return $items;
    }

    /**
     * Attach a playlist to the screen with or without a specific organisation
     *
     * @param Manivelle\Models\Playlist $playlist The playlist
     * @param Manivelle\Models\Organisation|null $organisation The organisation
     * @return Manivelle\Models\Playlist The playlist
     */
    public function attachPlaylist(Playlist $playlist, $organisation = null)
    {
        $currentPlaylist = $this->playlists()
            ->where('playlists.type', $playlist->type)
            ->first();

        if ($currentPlaylist && empty($currentPlaylist->organisation_id) && $organisation) {
            $currentPlaylist->organisation_id = $organisation->id;
            $currentPlaylist->save();
        }

        if ($playlist && empty($playlist->organisation_id) && $organisation) {
            $playlist->organisation_id = $organisation->id;
            $playlist->save();
        }

        if ($currentPlaylist && $currentPlaylist->id !== $playlist->id) {
            $this->playlists()->detach($currentPlaylist);
        }

        if (!$currentPlaylist || $currentPlaylist->id !== $playlist->id) {
            $this->playlists()->attach($playlist);
            event(new ScreenPlaylistAttached($this, $playlist));
        }

        return $playlist->fresh();
    }

    /**
     * Detach a playlist from a screen with or without a specific organisation
     *
     * @param Manivelle\Models\Playlist $playlist The playlist
     * @param Manivelle\Models\Organisation|null $organisation The organisation
     * @return Manivelle\Models\Playlist The playlist
     */
    public function detachPlaylist(Playlist $playlist, $organisation = null)
    {
        $query = $this->playlists()->where('playlists.id', $playlist->id);
        if ($organisation) {
            $query->where('screens_playlists_pivot.organisation_id', $organisation->id);
        }
        $current = $query->first();

        if ($current) {
            $this->playlists()->detach($current);
            event(new ScreenPlaylistDetached($this, $playlist));
        }

        return $playlist->fresh();
    }

    /**
     * Attach a channel to the screen with or without a specific organisation
     *
     * @param Manivelle\Models\Channel $channel The channel
     * @param Manivelle\Models\Organisation|null $organisation The organisation
     * @return Manivelle\Models\ScreenChannel The screen channel
     */
    public function attachChannel(Channel $channel, $organisation = null)
    {
        $screenChannel = new ScreenChannel();
        $table = $screenChannel->getTable();
        $query = $this->channels()->where($table . '.channel_id', $channel->id);
        if ($organisation) {
            $query->where($table . '.organisation_id', $organisation->id);
        }

        $current = $query->first();
        if ($current) {
            return $current;
        }

        $screenChannel->channel_id = $channel->id;
        $screenChannel->organisation_id = $organisation ? $organisation->id : 0;
        $this->channels()->save($screenChannel);

        event(new ScreenChannelAdded($this, $screenChannel));

        return $query->first();
    }

    /**
     * Detach a channel to the screen with or without a specific organisation
     *
     * @param Manivelle\Models\Channel $channel The channel
     * @param Manivelle\Models\Organisation|null $organisation The organisation
     * @return Manivelle\Models\ScreenChannel The screen channel
     */
    public function detachChannel(Channel $channel, $organisation = null)
    {
        $table = with(new ScreenChannel())->getTable();
        $query = $this->channels()->where($table . '.channel_id', $channel->id);
        if ($organisation) {
            $query->where($table . '.organisation_id', $organisation->id);
        }
        $current = $query->first();

        if ($current) {
            $current->delete();
            event(new ScreenChannelRemoved($this, $current));
        }

        return $current;
    }

    /**
     * Add a ping to the screen
     *
     * @param array $data The ping data
     * @return Manivelle\Models\ScreenPing The screen ping
     */
    public function addPing($data = [])
    {
        $ping = new ScreenPing();
        $ping->screen_id = $this->id;
        $ping->fill($data);
        $ping->save();

        return $ping;
    }

    /**
     * Add a command to the screen
     *
     * @param string $command The command
     * @param string $args The command arguments
     * @param string $data Othe command data
     * @return Manivelle\Models\ScreenCommand The screen command
     */
    public function addCommand($command, $args = [], $data = [])
    {
        $item = new ScreenCommand();
        $item->screen_id = $this->id;
        $item->command = $command;
        $item->arguments = $args;
        $item->fill($data);
        $item->save();

        return $item;
    }

    /**
     * Save settings for a specific screen channel.
     *
     * @param Manivelle\Models\Channel|integer $channel The channel or channel id
     * @param array|StdClass $settings The settings
     * @return Manivelle\Models\ScreenChannel The screen channel
     */
    public function saveChannelSettings($channel, $settings)
    {
        $channelId = is_object($channel) ? $channel->id : $channel;

        $table = with(new ScreenChannel())->getTable();
        $query = $this->channels()->where($table . '.channel_id', $channelId);
        $screenChannel = $query->first();

        if ($screenChannel) {
            $screenChannel->settings = $settings;
            $screenChannel->save();
        }

        $dispatcher = self::getEventDispatcher();
        if ($dispatcher) {
            $dispatcher->fire(new ScreenChanged($this));
        }

        return $query->first();
    }

    /**
     * Accessors and Mutators
     */
    protected function getOnlineAttribute()
    {
        if (!$this->last_ping) {
            return false;
        }

        $now = Carbon::now();
        $lastPingDate = $this->last_ping->created_at;

        return $now->diffInMinutes($lastPingDate) < config('manivelle.screens.last_ping_max');
    }

    protected function getLinkedAttribute()
    {
        $this->loadIfNotLoaded(['organisations']);
        return !$this->organisations || $this->organisations->isEmpty() ? false : true;
    }

    public function getUrlAttribute()
    {
        return route('screen.home', [$this->uuid]);
    }

    public function getLastUpdate()
    {
        $lastUpdate = $this->updated_at;
        foreach ($this->playlists as $playlist) {
            if (!$lastUpdate || $playlist->updated_at->gt($lastUpdate)) {
                $lastUpdate = $playlist->updated_at;
            }
        }
        foreach ($this->channels as $channel) {
            if (!$lastUpdate || $channel->updated_at->gt($lastUpdate)) {
                $lastUpdate = $channel->updated_at;
            }
            $lastBubbleDate = $channel
                ->bubbles()
                ->with('bubbles.bubble')
                ->select('*', 'bu.updated_at as bubble_updated_at')
                ->join('bubbles as bu', 'bu.id', '=', 'channels_bubbles_pivot.bubble_id')
                ->orderBy('bu.updated_at', 'desc')
                ->limit(1)
                ->lists('bubble_updated_at');
            $bubbleUpdateDate =
                $lastBubbleDate && sizeof($lastBubbleDate)
                    ? Carbon::parse($lastBubbleDate[0])
                    : null;
            if ($bubbleUpdateDate && (!$lastUpdate || $bubbleUpdateDate->gt($lastUpdate))) {
                $lastUpdate = $bubbleUpdateDate;
            }
        }

        return $lastUpdate;
    }

    /**
     * Get the stats of a screen
     *
     * @return array Stats
     */
    public function getStats()
    {
        return Manivelle::cache('screen_stats')
            ->setItem($this)
            ->get();
    }

    /**
     * Check if every caches are ready for a specific cache version
     *
     * @param  integer  $version The version to check
     * @return boolean Whether it has all caches or no
     */
    public function hasAllCachesForVersion($version)
    {
        $allCaches = Manivelle::caches(Screen::class);
        $screenCaches = $this->caches()
            ->where('version', '>=', $version)
            ->get();

        foreach ($allCaches as $cache) {
            $screenCache = $screenCaches->first(function ($key, $value) use ($cache) {
                return $value->name === $cache;
            });
            if (!$screenCache) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the next version number for a specific cache
     *
     * @param  string  $cache The cache name
     * @return integer The new cache version number
     */
    public function getNextCacheVersion($cache)
    {
        $lastCache = $this->caches()
            ->where('name', $cache)
            ->orderBy('version', 'desc')
            ->first();

        if ($lastCache) {
            $currentCache = $this->caches()
                ->where('name', '!=', $cache)
                ->where('version', '>', $lastCache->version + 1)
                ->orderBy('version', 'desc')
                ->first();
            if ($currentCache) {
                return $currentCache->version;
            }
        }

        return $lastCache ? $lastCache->version + 1 : 1;
    }

    /**
     * Get all bubbles ids for the screen. Contains all bubbles from the screen
     * channels (including filters) and all bubbles from the playlists.
     *
     * @return array The list of bubbles ids
     */
    public function getBubbleIds()
    {
        $organisations = $this->organisations->pluck('id')->toArray();
        $ids = [];

        /**
         * Get screen channels
         */
        $channels = ScreenChannel::where('screen_id', $this->id)
            ->with('channel')
            ->get();

        if (!$channels || !sizeof($channels)) {
            return $ids;
        }

        $channelWithoutFilters = $channels
            ->filter(function ($channel) {
                return !isset($channel->settings->filters) ||
                    !is_array($channel->settings->filters) ||
                    !sizeof($channel->settings->filters);
            })
            ->unique()
            ->values();
        $channelWithFilters = $channels
            ->filter(function ($channel) use ($channelWithoutFilters) {
                return !$channelWithoutFilters->contains($channel);
            })
            ->unique()
            ->values();

        /**
         * Get ids from channels without filters
         */
        if (sizeof($channelWithoutFilters)) {
            $byOrganisation = $channelWithoutFilters->filter(function ($channel) {
                return $channel->bubblesAreByOrganisation();
            });
            $anyOrganisation = $channelWithoutFilters->filter(function ($channel) {
                return !$channel->bubblesAreByOrganisation();
            });
            $bubbleIds = new Collection();
            if (sizeof($byOrganisation)) {
                $byOrganisationBubblesIds = Panneau::resource('bubbles')
                    ->query([
                        'channel_id' => $byOrganisation->pluck('id')->toArray(),
                        'organisation_id' => $organisations,
                    ])
                    ->lists('bubbles.id');
                $bubbleIds = $bubbleIds->merge($byOrganisationBubblesIds);
            }
            if (sizeof($anyOrganisation)) {
                $anyOrganisationBubblesIds = Panneau::resource('bubbles')
                    ->query([
                        'channel_id' => $anyOrganisation->pluck('id')->toArray(),
                    ])
                    ->lists('bubbles.id');
                $bubbleIds = $bubbleIds->merge($anyOrganisationBubblesIds);
            }
            foreach ($bubbleIds as $id) {
                $ids[] = $id;
            }
        }

        /**
         * Get ids from channels with filters
         */
        if (sizeof($channelWithFilters)) {
            foreach ($channelWithFilters as $channel) {
                $params = [
                    'channel_id' => $channel->channel_id,
                ];
                $filters = $channel->settings->filters;

                foreach ($filters as $filter) {
                    if (isset($filter->value) && isset($filter->name) && !empty($filter->name)) {
                        if (!isset($params['filter_' . $filter->name])) {
                            $params['filter_' . $filter->name] = [];
                        }
                        if (is_array($filter->value)) {
                            $notEmptyValues = array_where($filter->value, function ($key, $value) {
                                return !empty($value);
                            });
                            if (sizeof($notEmptyValues)) {
                                $params['filter_' . $filter->name] = array_unique(
                                    array_merge($params['filter_' . $filter->name], $notEmptyValues)
                                );
                            }
                        } elseif (!empty($filter->value)) {
                            $params['filter_' . $filter->name][] = $filter->value;
                        }
                    }
                }

                $notEmptyParams = array_where($params, function ($key, $value) {
                    return !empty($value);
                });

                if ($channel->bubblesAreByOrganisation() && sizeof($organisations)) {
                    $notEmptyParams['organisation_id'] = $organisations;
                }

                $bubbleIds = Panneau::resource('bubbles')
                    ->query($notEmptyParams)
                    ->lists('bubbles.id');
                foreach ($bubbleIds as $id) {
                    $ids[] = $id;
                }
            }
        }

        /**
         * Get ids from playlists
         */
        $screenId = $this->id;
        $playlists = Playlist::whereHas('screens', function ($query) use ($screenId) {
            $query->where('screens.id', $screenId);
        })
            /*->join(
            'screens_playlists_pivot',
            'screens_playlists_pivot.playlist_id',
            '=',
            'playlists.id'
        )
        ->where('screens_playlists_pivot.screen_id', $this->id)*/
            ->get();
        foreach ($playlists as $playlist) {
            $timeline = $playlist->getTimeline();
            foreach ($timeline->bubbleIds as $bubbleId) {
                $ids[] = $bubbleId;
            }
        }

        $ids = array_unique($ids);
        sort($ids);

        return $ids;
    }
}
