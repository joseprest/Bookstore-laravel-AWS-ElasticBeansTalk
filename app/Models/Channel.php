<?php namespace Manivelle\Models;

use Closure;
use Cache;

use Manivelle;
use Manivelle\User;

use Illuminate\Database\Eloquent\Model;

use Panneau\Bubbles\Models\Bubble as BaseBubble;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use Event;
use Manivelle\Events\ChannelUpdated;

use Manivelle\Models\Traits\LoadTrait;

class Channel extends BaseBubble implements SluggableInterface
{
    use LoadTrait, SluggableTrait;

    protected $table = 'channels';

    protected $visible = [
        'id',
        'type',
        'handle',
        'snippet',
        'fields',
        'filters',
        'bubbles_filters',
    ];

    protected $fillable = [
        'type',
        'handle',
        'notifications',
        'slideshow',
        'settings',
        'organisation_id',
    ];

    protected $appends = ['filters', 'bubbles_filters', 'snippet', 'fields'];

    protected $sluggable = [
        'build_from' => 'type',
        'save_to' => 'handle',
    ];

    protected $casts = [
        'settings' => 'object',
    ];

    protected $channelType;

    protected $appends_filters_values = true;

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();
    }

    /**
     * Relationships
     */
    public function screens()
    {
        return $this->belongsToMany(
            \Manivelle\Models\Screen::class,
            'screens_channels_pivot',
            'channel_id',
            'screen_id'
        )
            ->withPivot('id', 'channel_id', 'screen_id', 'settings', 'organisation_id')
            ->withTimestamps();
    }

    public function organisations()
    {
        return $this->belongsToMany(
            \Manivelle\Models\Organisation::class,
            'screens_channels_pivot',
            'channel_id',
            'organisation_id'
        )
            ->withPivot('id', 'channel_id', 'organisations_id', 'settings')
            ->withTimestamps();
    }

    public function bubbles()
    {
        return $this->hasMany(\Manivelle\Models\ChannelBubble::class, 'channel_id');
    }

    public function users()
    {
        return $this->hasMany(\Manivelle\Models\ChannelUser::class, 'channel_id');
    }

    /**
     * Accessors and mutators
     */
    protected function getFiltersAttribute()
    {
        if (!$this->appends_filters_values) {
            $items = $this->getFilters();
            return $this->getFiltersWithoutClosure($items);
        }

        return Manivelle::cache(self::class, 'filters')
            ->setItem($this)
            ->getData();
    }

    protected function getBubblesFiltersAttribute()
    {
        if (!$this->appends_filters_values) {
            $items = $this->getBubblesFilters();
            return $this->getFiltersWithoutClosure($items);
        }

        return Manivelle::cache(self::class, 'bubbles_filters')
            ->setItem($this)
            ->getData();
    }

    protected function getFiltersWithoutClosure($items)
    {
        $filters = [];
        foreach ($items as $item) {
            $filter = [];
            foreach ($item as $key => $value) {
                if (!$value instanceof Closure) {
                    $filter[$key] = $value;
                }
            }
            $filters[] = $filter;
        }
        return $filters;
    }

    /**
     * Check if the channel accept bubble creation
     *
     * @return boolean Whether the channel accept or not creation
     */
    public function canAddBubbles()
    {
        return isset($this->fields->settings->canAddBubbles) &&
            $this->fields->settings->canAddBubbles
            ? true
            : false;
    }

    /**
     * Check if the channel bubbles are filtered by organisation
     *
     * @return boolean Whether the bubbles are filtered
     */
    public function bubblesAreByOrganisation()
    {
        return isset($this->fields->settings->bubblesByOrganisation) &&
            $this->fields->settings->bubblesByOrganisation
            ? true
            : false;
    }

    /**
     * Get channel type filters
     *
     * @return array The list of filters
     */
    public function getFilters()
    {
        return $this->getChannelType()->getFilters();
    }

    /**
     * Get channel type bubbles filters
     *
     * @return array The list of bubbles filters
     */
    public function getBubblesFilters()
    {
        return $this->getChannelType()->getBubblesFilters();
    }

    /**
     * Hide filters from the array of json representation of the bubble
     *
     * @return void
     */
    public function withoutFilters()
    {
        $this->setAppends(['snippet', 'fields']);
        $this->setHidden(['filters', 'bubbles_filters']);
    }

    /**
     * Remove values from the filters
     *
     * @return $this
     */
    public function withoutFiltersValues()
    {
        $this->appends_filters_values = false;
        return $this;
    }

    /**
     * Add values to the filters
     *
     * @return $this
     */
    public function withFiltersValues()
    {
        $this->appends_filters_values = true;
        return $this;
    }

    /**
     * Get Channel Type
     *
     * @return Manivelle\Support\ChannelType The channel type class
     */
    public function getChannelType()
    {
        if ($this->channelType) {
            return $this->channelType;
        }

        try {
            $this->channelType = app('manivelle')->channelType($this->type);
        } catch (\Exception $e) {
            $this->channelType = app('manivelle')->channelType('channel');
        }
        $this->channelType->withModel($this);
        return $this->channelType;
    }

    /**
     * Get bubble type which in this case is a ChannelType
     *
     * @return Manivelle\Support\ChannelType The channel type class
     */
    protected function getBubbleType()
    {
        return $this->getChannelType();
    }

    /**
     * Add a bubble to the channel
     *
     * @param Manivelle\Models\Bubble $bubble The bubble to add
     *
     * @return Manivelle\Models\ChannelBubble $bubble The ChannelBubble created
     */
    public function addBubble(Bubble $bubble)
    {
        $query = $this->bubbles()->where('bubble_id', $bubble->id);
        $current = $query->first();

        if ($current) {
            return $current;
        }

        $channelBubble = new ChannelBubble();
        $channelBubble->bubble_id = $bubble->id;
        $this->bubbles()->save($channelBubble);

        $this->touch();

        return $query->first();
    }

    /**
     * Removes a bubble from this channel. If after the remove the bubble is not associated with any
     * channel, it is deleted. Returns the removed bubble, or null if no bubble were removed.
     *
     * @param Bubble|string $bubbleRaw Bubble instance or bubble id
     * @return Bubble
     */
    public function removeBubble($bubbleRaw)
    {
        $bubble_id = $bubbleRaw instanceof Bubble ? $bubbleRaw->id : $bubbleRaw;
        $channelBubble = $this->bubbles()
            ->where('bubble_id', $bubble_id)
            ->first();

        if ($channelBubble) {
            $channelBubble->delete();
            $bubble = $channelBubble->bubble;
            $bubbleChannelCounts = ChannelBubble::where('bubble_id', $bubble_id)->count();
            if ($bubbleChannelCounts === 0) {
                $bubble->delete();
            }
            return $bubble;
        }

        return null;
    }
}
