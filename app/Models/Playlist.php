<?php namespace Manivelle\Models;

use Manivelle\User;

use Illuminate\Database\Eloquent\Model;

use Manivelle\Timeline;

use Event;
use Manivelle\Events\PlaylistItemAdded;
use Manivelle\Events\PlaylistItemRemoved;
use Manivelle\Events\PlaylistItemsOrderChanged;

use Manivelle\Models\Traits\LoadTrait;

class Playlist extends Model
{
    use LoadTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'playlists';

    protected $touches = [
        /*'screens'*/
    ];

    protected $fillable = [
        'type',
        'name'
    ];

    protected $hidden = [
        'items'
    ];

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
    public function organisation()
    {
        return $this->belongsTo('Manivelle\Models\Organisation', 'organisation_id');
    }

    public function condition()
    {
        return $this->belongsTo('Manivelle\Models\Condition', 'condition_id');
    }

    public function screens()
    {
        return $this->belongsToMany('Manivelle\Models\Screen', 'screens_playlists_pivot', 'playlist_id', 'screen_id')
                    ->withPivot('id', 'playlist_id', 'screen_id', 'settings', 'condition_id')
                    ->withTimestamps();
    }

    public function bubbles()
    {
        return $this->belongsToMany('Manivelle\Models\Bubble', 'playlists_bubbles_pivot', 'playlist_id', 'bubble_id')
                    ->withPivot('id', 'order', 'settings', 'condition_id')
                    ->withTimestamps();
    }

    public function items()
    {
        return $this->hasMany(\Manivelle\Models\PlaylistItem::class, 'playlist_id')
                    ->orderBy('playlists_bubbles_pivot.order', 'ASC');
    }

    public function users()
    {
        return $this->hasMany(\Manivelle\Models\PlaylistUser::class, 'playlist_id');
    }

    /**
     * Get the timeline for the playlist
     *
     * @param  [type] $start Start date from which the timeline start
     * @param  [type] $end   End date at which the timeline end
     * @return Manivelle\Timeline The playlist timeline
     */
    public function getTimeline($start = null, $end = null)
    {
        $this->loadIfNotLoaded([
            'items',
            'items.bubble.texts',
            'items.bubble.metadatas',
            'items.bubble.pictures',
            'items.condition',
            'items.condition.texts',
            'items.condition.metadatas'
        ]);

        $items = $this->items->sortBy('order');
        return Timeline::makeFromItems($items, $start, $end);
    }

    /**
     * Add a Bubble to the playlist
     *
     * @param Manivelle\Models\Bubble $bubble The bubble to add
     * @param array $itemData Additional item data
     * @return Manivelle\Models\PlaylistItem The new playlist item
     */
    public function addItem(Bubble $bubble, $itemData = [])
    {
        $table = with(new PlaylistItem())->getTable();
        if (!isset($itemData['order'])) {
            $lastItem = $this->items()
                                ->orderBy($table.'.order', 'desc')
                                ->first();
            $itemData['order'] = $lastItem ? ($lastItem->order+1):0;
        }

        $playlistItem = new PlaylistItem();
        $playlistItem->bubble_id = $bubble->id;
        $playlistItem->fill($itemData);
        $this->items()->save($playlistItem);

        $dispatcher = self::getEventDispatcher();
        if ($dispatcher) {
            $dispatcher->fire(new PlaylistItemAdded($this, $playlistItem));
        }

        return $this->items()
                        ->with(
                            'bubble.texts',
                            'bubble.metadatas',
                            'bubble.pictures',
                            'condition',
                            'condition.texts',
                            'condition.metadatas'
                        )
                        ->find($playlistItem->id);
    }

    /**
     * Remove an item from the playlist
     *
     * @param Manivelle\Models\PlaylistItem|integer $item The playlist item or id
     * @return Manivelle\Models\PlaylistItem The removed playlist item
     */
    public function removeItem($item)
    {
        if (!is_object($item)) {
            $id = $item;
            $item = $this->items->first(function ($key, $item) use ($id) {
                return (string)$item->id === (string)$id;
            });
        }

        if ($item) {
            if (isset($item->bubble) && $item->bubble->type === 'filter') {
                $item->bubble->delete();
            }
            $item->delete();
        }

        $this->updateItemsOrder();

        $dispatcher = self::getEventDispatcher();
        if ($dispatcher) {
            $dispatcher->fire(new PlaylistItemRemoved($this, $item));
        }

        return $item;
    }

    /**
     * Update playlist items order
     *
     * @param array $ids The list of items ids
     * @return Illuminate\Support\Collection The list of items
     */
    public function updateOrder($ids)
    {
        $items = $this->items;
        $items = $items->sort(function ($a, $b) use ($ids) {
            $aIndex = array_search((int)$a->id, $ids);
            $bIndex = array_search((int)$b->id, $ids);
            if ($aIndex === false) {
                return 1;
            }
            if ($bIndex === false) {
                return 1;
            }
            return $aIndex < $bIndex ? -1:1;
        });

        $this->updateItemsOrder($items);

        $dispatcher = self::getEventDispatcher();
        if ($dispatcher) {
            $dispatcher->fire(new PlaylistItemsOrderChanged($this, $items));
        }

        return $this->items()
                        ->with([
                            'bubble.texts',
                            'bubble.metadatas',
                            'bubble.pictures',
                            'condition',
                            'condition.texts',
                            'condition.metadatas'
                        ])
                        ->get();
    }

    /**
     * Update items order
     *
     * @param Illuminate\Support\Collection|array|null $items The list of items
     * @return Illuminate\Support\Collection The list of items
     */
    protected function updateItemsOrder($items = null)
    {
        if (!$items) {
            $this->load('items');
            $items = $this->items;
        }

        $order = 0;
        foreach ($items as $item) {
            $item->order = $order;
            $item->save();
            $order++;
        }

        return $items;
    }
}
