<?php namespace Manivelle\Models;

use DB;
use Manivelle;
use Cache;
use App;

use Illuminate\Database\Eloquent\Model;
use Manivelle\Models\Collections\Bubbles as BubblesCollection;

use Panneau\Bubbles\Models\Bubble as BaseBubble;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use Manivelle\Models\Traits\LoadTrait;
use Manivelle\Models\Traits\HasFieldPersons;
use Manivelle\Models\Traits\HasFieldCategories;
use Manivelle\Models\Traits\HasFieldLocations;

use Manivelle\Models\Screen;

class Bubble extends BaseBubble implements SluggableInterface
{
    use LoadTrait, SluggableTrait, HasFieldPersons, HasFieldCategories, HasFieldLocations;

    protected $fillable = [
        'type',
        'handle',
        'source_id',
        'organisation_id',
        'settings',
        'published'
    ];

    protected $visible = [
        'id',
        'type',
        'handle',
        'settings',
        'published',
        'fields',
        'snippet'
    ];

    protected $appends = [
        'fields',
        'snippet'
    ];

    protected $casts = [
        'settings' => 'object'
    ];

    protected $with = [
        'texts',
        'pictures',
        'metadatas',
        'fields_persons',
        'fields_categories',
        'fields_locations'
    ];

    protected $sluggable = [
        'build_from' => 'type',
        'save_to'    => 'handle'
    ];

    protected $touches = [
        'channels',
        'playlists'
    ];

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        self::deleting(function ($model) {
            $model->clearCaches();
        });
    }

    /**
     * Relationships
     */
    public function source()
    {
        return $this->belongsTo(\Manivelle\Models\Source::class, 'source_id');
    }

    public function organisation()
    {
        return $this->belongsTo(\Manivelle\Models\Organisation::class, 'organisation_id');
    }

    public function channels()
    {
        return $this->belongsToMany('Manivelle\Models\Channel', 'channels_bubbles_pivot', 'bubble_id', 'channel_id')
                    ->withPivot('id', 'order', 'settings')
                    ->withTimestamps();
    }

    public function playlists()
    {
        return $this->belongsToMany('Manivelle\Models\Playlist', 'playlists_bubbles_pivot', 'bubble_id', 'playlist_id')
                    ->withPivot('id', 'order', 'settings', 'condition_id')
                    ->withTimestamps();
    }

    /**
     * Accessors and mutators
     */
    public function newCollection(array $models = [])
    {
        return BubblesCollection::make($models);
    }

    /**
     * Get the bubble type
     *
     * @return Manivelle\Support\BubbleType The bubble type class
     */
    public function bubbleType()
    {
        return $this->getBubbleType();
    }

    /**
     * Get bubble suggestions
     *
     * @return array List of bubble ids
     */
    public function getSuggestions()
    {
        return Manivelle::cache(self::class, 'suggestions')
                            ->setItem($this)
                            ->get();
    }

    /**
     * Clear caches
     *
     * @return void
     */
    public function clearCaches()
    {
        $locale = App::getLocale();

        if ($this->type === 'filter') {
            Cache::forget('bubble_filter_description_'.$this->id.'_'.$locale);
        }
    }
}
