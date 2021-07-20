<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

use Manivelle;

class ScreenChannel extends ChannelPivot
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'screens_channels_pivot';

    protected $touches = [
        'screen'
    ];

    protected $casts = [
        'settings' => 'object',
        'screen_id' => 'integer',
        'organisation_id' => 'integer',
        'channel_id' => 'integer'
    ];

    /**
     * Relationships
     */
    public function screen()
    {
        return $this->belongsTo(\Manivelle\Models\Screen::class, 'screen_id');
    }

    public function getSettingsFilters()
    {
        $filters = [];
        $channelType = $this->channel ? $this->channel->getChannelType():null;
        if (isset($this->settings) && isset($this->settings->filters) && $this->settings->filters) {
            $bubblesFilters = $channelType->getBubblesFilters();
            $items = $this->settings->filters;
            foreach ($items as $item) {
                if (!isset($item->name)) {
                    continue;
                }

                $filter = array_first($bubblesFilters, function ($key, $value) use ($item) {
                    return $item->name === $value['name'];
                });

                if (!$filter) {
                    continue;
                }

                if ($filter['type'] === 'tokens') {
                    $tokens = $channelType->getBubbleFilterTokens($filter['name']);
                    $item->tokens = [];
                    $value = $item->value;
                    $values = isset($value) && !empty(array_get($value, '0', $value)) ? (array)$value:null;
                    if ($values) {
                        foreach ($tokens as $token) {
                            if (in_array($token['value'], $values)) {
                                $item->tokens[] = $token;
                            }
                        }
                    }
                    if (!sizeof($item->tokens)) {
                        continue;
                    }
                }
                $filters[] = $item;
            }
        }

        return $filters;
    }
}
