<?php namespace Manivelle\Support;

use Manivelle;
use App;

use Panneau\Bubbles\Support\BubbleType as BaseBubbleType;

class ChannelType extends BaseBubbleType
{
    protected $defaultAttributes = array(
        'type' => 'channel'
    );

    protected $views = [];
    protected $filters = [];
    protected $settings = [];
    protected $bubblesFilters = [];

    public function fields()
    {
        $fields = [
            [
                'name' => 'name',
                'type' => 'text_locale'
            ],
            [
                'name' => 'icon',
                'type' => 'picture'
            ],
            [
                'name' => 'theme',
                'type' => 'channel_theme'
            ],
            [
                'name' => 'settings',
                'type' => 'channel_settings'
            ]
        ];

        $settings = $this->getSettings();
        foreach ($settings as $field) {
            $field['settings'] = true;
            $fields[] = $field;
        }

        return $fields;
    }

    public function views()
    {
        return [];
    }

    public function getViews()
    {
        $items = array_merge($this->views, $this->views());
        return $items;
    }

    public function getFilters()
    {
        $filters = array_merge($this->filters, $this->filters());
        return $filters;
    }

    public function getSettings()
    {
        $settings = array_merge($this->settings, $this->settings());
        return $settings;
    }

    public function getBubblesFilters()
    {
        $bubblesFilters = array_merge($this->bubblesFilters, $this->bubblesFilters());
        return $bubblesFilters;
    }


    public function snippet()
    {
        return [
            'title' => function ($fields, $model) {
                $locale = App::getLocale();
                return isset($fields->name) && !empty($fields->name->{$locale}) ? $fields->name->{$locale}:$model->handle;
            },
            'subtitle' => function ($fields, $model) {
                return null;
            },
            'description' => function ($fields, $model) {
                $locale = App::getLocale();
                return isset($fields->description) && !empty($fields->description->{$locale}) ? $fields->description->{$locale}:'';
            },
            'picture' => function ($fields, $model) {
                return $fields->icon ? $fields->icon:null;
            }
        ];
    }

    public function settings()
    {
        return [];
    }

    public function filters()
    {
        return [];
    }

    public function bubblesFilters()
    {
        $items = [];

        if (!isset($this->bubbleType) || !$this->bubbleType) {
            return $items;
        }

        $bubbleType = Manivelle::bubbleType($this->bubbleType);
        $typeNamespace = $bubbleType->type;
        $bubbleFilters = $bubbleType->getFilters();
        $channelFilters = $this->getFilters();

        foreach ($bubbleFilters as $filter) {
            $item = $filter;
            if (isset($item['value'])) {
                unset($item['value']);
            }
            if (isset($item['queryScope'])) {
                unset($item['queryScope']);
            }
            $name = $item['name'];
            $channelFilter = array_first($channelFilters, function ($key, $value) use ($name) {
                return $value['name'] === $name;
            });
            if ($channelFilter && !isset($item['values']) && isset($channelFilter['values'])) {
                $item['values'] = $channelFilter['values'];
            }
            $item['name'] = strtolower(studly_case($typeNamespace)).'_'.$item['name'];
            $items[] = $item;
        }

        return $items;
    }

    public function getTokens($name, $params = [], $opts = [])
    {
        $bubbleType = Manivelle::bubbleType($this->bubbleType);
        return $bubbleType->getTokens($name, $params, $opts);
    }

    public function getValues($name, $params = [], $opts = [])
    {
        $bubbleType = Manivelle::bubbleType($this->bubbleType);
        return $bubbleType->getValues($name, $params, $opts);
    }

    public function getBubbleFilterTokens($name)
    {
        return $this->getFilterCachedValues($name, 'tokens', 'bubbles_filters');
    }

    public function getBubbleFilterValues($name)
    {
        return $this->getFilterCachedValues($name, 'values', 'bubbles_filters');
    }

    public function getFilterTokens($name)
    {
        return $this->getFilterCachedValues($name, 'tokens');
    }

    public function getFilterValues($name)
    {
        return $this->getFilterCachedValues($name, 'values');
    }

    public function getFilterValuesCache($name, $valueName, $type)
    {
        $cacheName = ($type === 'filters' ? 'filter':'bubble_filter').'_'.$valueName;
        return Manivelle::cache(\Manivelle\Support\ChannelType::class, $cacheName)
            ->setItem([
                'channel_type' => $this->type,
                'type' => $type,
                'name' => $name
            ]);
    }

    public function getFilterCachedValues($name, $valueName = 'values', $type = 'filters')
    {
        $cache = $this->getFilterValuesCache($name, $valueName, $type);
        $filter = $cache->getFilter();
        $cacheable = array_get($filter, $valueName.'_cacheable', false);

        return $cacheable ? $cache->get():$cache->getData();
    }
}
