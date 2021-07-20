Documentation to create a new channel
===

In this doc, the following "variables" are used to refer to names derived from the new channel's name. For example, if the new channel's name is "Publication", the following variables have the following values :

|Variable       |Example if channel's name is "Publication"|
|---------------|------------------------------------------|
|{Bubble}       |Publication                               |
|{bubble}       |publication                               |
|{Channel}      |Publication                               |
|{Channels}     |Publications                              |
|{channels}     |publications                              |


Create the Bubble class
===
Create a new class in `app/Channels/{Channels}/{Bubble}Bubble.php`. This class has the following signature / attributes:

```php
namespace Manivelle\Channels\{Channels};

use Manivelle\Support\BubbleType;
use Manivelle\Models\Bubble;
use Manivelle\Contracts\Bubbles\Cleanable;

class {Bubble}Bubble extends BubbleType implements Cleanable {
    protected $attributes = [
        'type' => '{bubble}'
    ];
}
```

And has the following methods:

```php
/**
 * Attributes of the bubble. Will probably return an array with a single element: 'label' and the
 * localized name of the bubble (see below for localization)
 *
 * @return array
 */
public function attributes

/**
 * Returns an array with the fields for this bubble. Each item is an array with the following
 * structure:
 * [
 *     'name' => // (string) Field 'code' (ex: 'language')
 *     'type' => // (string) Field 'type' (ex: 'persons'), see app/Panneau/Fields, for example.
 *     'label' => // (string) visible name of the field (localized, see below)
 * ]
 *
 * @return array
 */
public function fields

/**
 * Array of the fields used for suggestions (?). Returns an array of fields 'name'
 *
 * @return array
 *
 */
public function suggestions

/**
 * Returns snippet data used to build this bubble's snippet on the frontend. Returns an associative
 * array filled with data necessary for the frontend.
 *
 * @return  array
 */
public function snippet

/**
 * All filters available for this bubble. Returns an array of filters where each filter is an array
 * with the following fields :
 *
 * [
 *     'name' => // (string) filter name
 *     'type' => // (string) filter type (ex: 'tokens', 'select', see, for ex., app/Panneau/Fields)
 *     'label' => // (string) localized filter name
 *     'multiple' => // (boolean) (?)
 *     'queryScope' => // (function) (?)
 *     'value' => // (mixed) current value of the field
 *     'tokens' => // (optional, array) available values for filters with multiple values
 *         (ex: 'type' === 'select' or 'tokens')
 * ]
 */
public function filters
```

For localized strings, create a file in `resources/lang/[lang-code]/bubbles/{bubble}.php`

Create the Channel class
===
Create a new class in `app/Channels/{Channels}/{Channels}Channel.php`. This class has the following signature / attributes:

```php
namespace Manivelle\Channels\Publications;

use Manivelle\Support\ChannelType;

class PublicationsChannel extends ChannelType
{
    protected $attributes = [
        'type' => '{channels}',
        'bubbleType' => '{bubble}'
    ];
}
```

And it has the following methods :

```php
/**
 * Array of the filters available on the frontend (for a channel, the different "views" for the list
 * of bubbles). Each item is an array with the following fields :
 * [
 *     'name' => // (string) name ("code") of the filter
 *     'label' => // (string) localized name displayed for the filter (name of tab in frontend)
 *     'field' => // (string) type of field for this filter
 *     'type' => // (string) type of visual representation for the bubbles (see in frontend types
 *         of lists)
 *     'values' => // (array) values for the bubble 'groups' where each element is an array with
 *         'label' and 'value' keys
 *     ... // other fields used in the frontend
 * ]
 */
public function filters
```

For localized strings, create a file in `resources/lang/[lang-code]/channels/{channels}.php`

Create the service provider and register it
===

Create a new class in `app/Channels/{Channels}/{Channels}ServiceProvider.php` :

```php
namespace Manivelle\Channels\{Channels};

use Manivelle\Support\ChannelServiceProvider;

class {Channels}ServiceProvider extends ChannelServiceProvider
{
    protected $channelTypes = [
        \Manivelle\Channels\{Channels}\{Channels}Channel::class
    ];

    protected $bubbleTypes = [
        \Manivelle\Channels\{Channels}\{Bubble}Bubble::class
    ];

    protected $fields = [];
}
```

And add this service provider to the config in `config/app.php` :

```php
'providers' => [
    // ...
    Manivelle\Channels\{Channels}\{Channels}ServiceProvider::class,
    // ...
],
```

Add the Channel to the database seeder
===
Add the channel to the file `database/seeds/ChannelSeeder.php` and fill accordingly (some attributes of the following code need setup) :

```php
protected $items = [
    // ...
    [
        'type' => '{channels}',
        'handle' => '{channels}',
        'fields' => array(
            'name' => array(
                'fr' => /* (string) French name of the channel (visible in frontend)*/,
                'en' => /* (string) English name */,
            ),

            /**
             * Frontend styles
             */
            'theme' => [
                'color_light' => '#D23A26', //Button, highlight
                'color_medium' => '#551109', //Details modal background
                'color_normal' => '#65150B', //Details view background
                'color_dark' => '#3E0C06', //Background
                'color_darker' => '#2B0804', //Modal background
                'color_shadow' => '#9B1F14', //Shadow button et button inactive
                'color_shadow_darker' => '#98221B' //Shadow button details
            ],

            /**
             * Frontend display settings
             */
            'settings' => [
                'channelView' => 'filters_circles',
                'colorPalette' => 'skittles',
                'modalBubblesListView' => 'cover',

                'slidesHeightRatio' => 0.35,
                'slidesMarginRatio' => 0.05,
                'slidesWidthRatio' => null,
                'slidesSlideView' => 'thumbnail',

                'bubbleDetailsShowTypeName' => true,
                'bubbleDetailsShowTitle' => true,
                'bubbleDetailsContentView' => 'columns',
                'bubbleDetailsContentColumns' => [
                    [
                        'column' => 0,
                        'type' => 'fields',
                        /**
                         * Fields available in the channel JSON
                         */
                        'value' => 'fields.authors,fields.publisher,fields.date,fields.subjects,fields.characters,fields.awards,fields.genres'
                    ],
                    [
                        'column' => 0,
                        'type' => 'buttons',
                        'value' => 'send-bubble'
                    ]
                ]
            ],

            'icon' => 'seeders/channels/{channels}.svg'
        )
    ],
    // ...
];
```

Run the modified seeder with `php artisan db:seed --class=ChannelSeeder`

Add the icon
===

Add an SVG icon in `seeders/channels/{channels}.svg`. This icon is used in the frontend.
