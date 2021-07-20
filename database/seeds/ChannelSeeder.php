<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Manivelle\Models\Organisation;

class ChannelSeeder extends Seeder
{
    protected $items = [
        [
            'type' => 'books',
            'handle' => 'books',
            'fields' => array(
                'name' => array(
                    'fr' => 'Livres numériques',
                    'en' => 'Digital books',
                ),

                'theme' => [
                    'color_light' => '#3a2f81', //Button, highlight
                    'color_normal' => '#2B235C', //Details view background
                    'color_medium' => '#332978', //Send bubble background
                    'color_dark' => '#1E193D', //Background
                    'color_darker' => '#0E092B', //Modal background
                    'color_shadow' => '#291E63', //Shadow button et button inactive
                    'color_shadow_darker' => '#1F1B40', //Shadow button details
                ],

                'settings' => [
                    'channelView' => 'filters_tabs',

                    'slidesHeightRatio' => 0.35,
                    'slidesMarginRatio' => 0.05,
                    'slidesWidthRatio' => null,
                    'slidesSlideView' => 'thumbnail',

                    'modalBubblesListView' => 'cover',

                    'bubbleDetailsShowTypeName' => true,
                    'bubbleDetailsShowTitle' => true,
                    'bubbleDetailsContentView' => 'columns',
                    'bubbleDetailsContentColumns' => [
                        [
                            'column' => 0,
                            'type' => 'description',
                            'value' => 'snippet.description',
                        ],
                        [
                            'column' => 1,
                            'type' => 'fields',
                            'value' =>
                                'fields.authors,fields.publisher,fields.categories,fields.date',
                        ],
                        [
                            'column' => 1,
                            'type' => 'buttons',
                            'value' => 'send-bubble',
                        ],
                    ],
                ],

                'icon' => 'seeders/channels/books.svg',
            ),
        ],

        [
            'type' => 'events',
            'handle' => 'events',
            'fields' => array(
                'name' => array(
                    'fr' => 'Événements',
                    'en' => 'Events',
                ),

                'theme' => [
                    'color_light' => '#C61D22', //Button, highlight
                    'color_medium' => '#391316', //Details modal background
                    'color_normal' => '#6A191D', //Details view background
                    'color_dark' => '#43171A', //Background
                    'color_darker' => '#391316', //Modal background
                    'color_shadow' => '#99131D', //Shadow button et button inactive
                    'color_shadow_darker' => '#550F13', //Shadow button details
                ],

                'settings' => [
                    'channelView' => 'filters_tabs',

                    'slidesHeightRatio' => 0.4,
                    'slidesMarginRatio' => 0.03,
                    'slidesWidthRatio' => 0.5,
                    'slidesSlideView' => 'with_basic_infos',

                    'modalBubblesListView' => 'events',

                    'bubbleSuggestionView' => 'with_dates',
                    'bubbleDetailsShowTypeName' => false,
                    'bubbleDetailsShowTitle' => false,
                    'bubbleDetailsContentView' => 'columns',
                    'bubbleDetailsContentColumns' => [
                        [
                            'column' => 0,
                            'type' => 'title',
                            'value' => 'fields.category.value',
                        ],
                        [
                            'column' => 0,
                            'type' => 'description',
                            'value' => 'snippet.description',
                        ],
                        [
                            'column' => 1,
                            'type' => 'fields',
                            'value' => 'fields.date,fields.venue,fields.room,fields.category',
                        ],
                        [
                            'column' => 1,
                            'type' => 'buttons',
                            'value' => 'send-bubble',
                        ],
                    ],
                ],

                'icon' => 'seeders/channels/events.svg',
            ),
        ],
        [
            'type' => 'banq_photos',
            'handle' => 'banq_photos',
            'organisation' => 'banq',
            'fields' => array(
                'name' => array(
                    'fr' => 'Photos d’archives',
                    'en' => 'Archive Photos',
                ),

                'theme' => [
                    'color_light' => '#29927D', //Button, highlight
                    'color_medium' => '#0A372E', //Details modal background
                    'color_normal' => '#0C4137', //Details view background
                    'color_dark' => '#092721', //Background
                    'color_darker' => '#07211C', //Modal background
                    'color_shadow' => '#1E6D5C', //Shadow button et button inactive
                    'color_shadow_darker' => '#266C5C', //Shadow button details
                ],

                'settings' => [
                    'channelView' => 'filters_tabs',

                    'slidesHeightRatio' => 0.5,
                    'slidesMarginRatio' => 0.03,
                    'slidesWidthRatio' => null,
                    'slidesSlideView' => 'thumbnail',

                    'modalBubblesListView' => 'cover',

                    'bubbleDetailsShowTypeName' => true,
                    'bubbleDetailsShowTitle' => true,
                    'bubbleDetailsContentView' => 'columns',
                    'bubbleDetailsContentColumns' => [
                        [
                            'column' => 0,
                            'type' => 'description',
                            'value' => 'snippet.description',
                        ],
                        [
                            'column' => 1,
                            'type' => 'fields',
                            'value' => 'fields.date,fields.location,fields.subjects,fields.authors',
                        ],
                        [
                            'column' => 1,
                            'type' => 'buttons',
                            'value' => 'send-bubble',
                        ],
                    ],
                ],

                'icon' => 'seeders/channels/banq-photos.svg',
            ),
        ],
        [
            'type' => 'banq_cards',
            'handle' => 'banq_cards',
            'organisation' => 'banq',
            'fields' => array(
                'name' => array(
                    'fr' => 'Cartes postales',
                    'en' => 'Postcards',
                ),

                'theme' => [
                    'color_light' => '#D99E40', //Button, highlight
                    'color_medium' => '#442A09', //Details modal background
                    'color_normal' => '#51320B', //Details view background
                    'color_dark' => '#3B1F04', //Background
                    'color_darker' => '#321A03', //Modal background
                    'color_shadow' => '#A86D0C', //Shadow button et button inactive
                    'color_shadow_darker' => '#A46B27', //Shadow button details
                ],

                'settings' => [
                    'channelView' => 'filters_tabs',

                    'slidesHeightRatio' => 0.5,
                    'slidesMarginRatio' => 0.03,
                    'slidesWidthRatio' => null,
                    'slidesSlideView' => 'thumbnail',

                    'modalBubblesListView' => 'cover',

                    'modalSendBubbleHasMessage' => true,
                    'modalSendBubbleDefaultMessage' =>
                        'J\'ai fait cette découverte dans les collections de BAnQ!',

                    'bubbleDetailsShowTypeName' => true,
                    'bubbleDetailsShowTitle' => true,
                    'bubbleDetailsContentView' => 'columns',
                    'bubbleDetailsContentColumns' => [
                        [
                            'column' => 0,
                            'type' => 'fields',
                            'value' => 'fields.date,fields.location,fields.subjects,fields.authors',
                        ],
                        [
                            'column' => 0,
                            'type' => 'buttons',
                            'value' => 'send-bubble',
                        ],
                    ],
                ],

                'icon' => 'seeders/channels/banq-cards.svg',
            ),
        ],
        [
            'type' => 'banq_quizz',
            'handle' => 'banq_quizz',
            'organisation' => 'banq',
            'fields' => array(
                'name' => array(
                    'fr' => 'Le saviez-vous?',
                    'en' => 'Did you know?',
                ),

                'theme' => [
                    'color_light' => '#71BDC4', //Button, highlight
                    'color_medium' => '#173d42', //Details modal background
                    'color_normal' => '#1C494F', //Details view background
                    'color_dark' => '#153135', //Background
                    'color_darker' => '#0E2224', //Modal background
                    'color_shadow' => '#3D7C84', //Shadow button et button inactive
                    'color_shadow_darker' => '#3D7C84', //Shadow button details
                ],

                'settings' => [
                    'channelView' => 'filters_cards',
                    'channelFilterName' => 'question_category',

                    'slideshowInfosView' => 'with_question',
                    'slideshowImageMaxWidth' => 0.3,

                    'slidesHeightRatio' => 0.48,
                    'slidesMarginRatio' => 0.04,
                    'slidesWidthRatio' => 0.4,
                    'slidesSlideView' => 'card',

                    'bubbleDetailsShowTypeName' => false,
                    'bubbleDetailsShowTitle' => false,
                    'bubbleSuggestionView' => null,
                    'bubbleDetailsContentView' => 'question',
                ],

                'icon' => 'seeders/channels/banq-quizz.svg',
            ),
        ],
        [
            'type' => 'banq_books',
            'handle' => 'banq_books',
            'organisation' => 'banq',
            'fields' => array(
                'name' => array(
                    'fr' => 'Romans@lire',
                    'en' => 'Novels@Read',
                ),

                'theme' => [
                    'color_light' => '#D23A26', //Button, highlight
                    'color_medium' => '#551109', //Details modal background
                    'color_normal' => '#65150B', //Details view background
                    'color_dark' => '#3E0C06', //Background
                    'color_darker' => '#2B0804', //Modal background
                    'color_shadow' => '#9B1F14', //Shadow button et button inactive
                    'color_shadow_darker' => '#98221B', //Shadow button details
                ],

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
                            'value' =>
                                'fields.authors,fields.publisher,fields.date,fields.subjects,fields.characters,fields.awards,fields.genres',
                        ],
                        [
                            'column' => 0,
                            'type' => 'buttons',
                            'value' => 'send-bubble',
                        ],
                    ],
                ],

                'icon' => 'seeders/channels/banq-books.svg',
            ),
        ],
        [
            'type' => 'banq_services',
            'handle' => 'banq_services',
            'organisation' => 'banq',
            'fields' => array(
                'name' => array(
                    'fr' => 'Services / Collections',
                    'en' => 'Services / Collections',
                ),

                'theme' => [
                    'color_light' => '#C61D22', //Button, highlight
                    'color_medium' => '#391316', //Details modal background
                    'color_normal' => '#6A191D', //Details view background
                    'color_dark' => '#43171A', //Background
                    'color_darker' => '#311113', //Modal background
                    'color_shadow' => '#99131D', //Shadow button et button inactive
                    'color_shadow_darker' => '#550F13', //Shadow button details
                ],

                'settings' => [
                    'channelView' => 'bubbles_cards',
                    'canAddBubbles' => true,
                    'slideMenuDestinationView' => 'channel:main',
                ],

                'icon' => 'seeders/channels/banq-services.svg',
            ),
        ],
        [
            'type' => 'publications',
            'handle' => 'publications',
            'organisation' => 'ubo',
            'fields' => array(
                'name' => array(
                    'fr' => 'ebooks Cairn',
                    'en' => 'ebooks Cairn',
                ),

                'theme' => [
                    'color_light' => '#D23A26', //Button, highlight
                    'color_medium' => '#551109', //Details modal background
                    'color_normal' => '#65150B', //Details view background
                    'color_dark' => '#3E0C06', //Background
                    'color_darker' => '#2B0804', //Modal background
                    'color_shadow' => '#9B1F14', //Shadow button et button inactive
                    'color_shadow_darker' => '#98221B', //Shadow button details
                ],

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
                            'type' => 'description',
                            'value' => 'snippet.description',
                        ],
                        [
                            'column' => 1,
                            'type' => 'fields',
                            'value' =>
                                'fields.authors,fields.publisher,fields.date,fields.collection,fields.categories',
                        ],
                        [
                            'column' => 1,
                            'type' => 'buttons',
                            'value' => 'send-bubble',
                        ],
                    ],
                ],

                'icon' => 'seeders/channels/publications.svg',
            ),
        ],
        [
            'type' => 'quizz',
            'handle' => 'quizz',
            'organisation' => 'saint-lambert',
            'fields' => array(
                'name' => array(
                    'fr' => 'Le saviez-vous?',
                    'en' => 'Did you know?',
                ),

                'theme' => [
                    'color_light' => '#71BDC4', //Button, highlight
                    'color_medium' => '#173d42', //Details modal background
                    'color_normal' => '#1C494F', //Details view background
                    'color_dark' => '#153135', //Background
                    'color_darker' => '#0E2224', //Modal background
                    'color_shadow' => '#3D7C84', //Shadow button et button inactive
                    'color_shadow_darker' => '#3D7C84', //Shadow button details
                ],

                'settings' => [
                    'channelView' => 'filters_cards',
                    'channelFilterName' => 'question_category',

                    // 'slideshowInfosView' => 'with_question',
                    'slideshowInfosView' => null,
                    'slideshowImageMaxWidth' => 0.3,

                    'slidesHeightRatio' => 0.48,
                    'slidesMarginRatio' => 0.04,
                    'slidesWidthRatio' => 0.4,
                    // 'slidesSlideView' => 'card',
                    'slidesSlideView' => 'with_basic_infos',

                    'bubbleDetailsExcludedButtons' => ['send-bubble'],
                    'bubbleDetailsShowTypeName' => false,
                    'bubbleDetailsShowTitle' => false,
                    'bubbleSuggestionView' => null,
                    'bubbleDetailsContentView' => 'question',
                ],

                'icon' => 'seeders/channels/banq-quizz.svg',
            ),
        ],
        [
            'type' => 'services',
            'handle' => 'alma_news',
            'organisation' => 'alma',
            'fields' => array(
                'name' => array(
                    'fr' => 'Nouvelle d\'Alma',
                    'en' => 'Nouvelle d\'Alma',
                ),

                'theme' => [
                    'color_light' => '#C61D22', //Button, highlight
                    'color_medium' => '#391316', //Details modal background
                    'color_normal' => '#6A191D', //Details view background
                    'color_dark' => '#43171A', //Background
                    'color_darker' => '#311113', //Modal background
                    'color_shadow' => '#99131D', //Shadow button et button inactive
                    'color_shadow_darker' => '#550F13', //Shadow button details
                ],

                'settings' => [
                    'channelView' => 'bubbles_cards',
                    'canAddBubbles' => true,
                    'slideMenuDestinationView' => 'channel:main',
                ],

                'icon' => 'seeders/channels/events.svg',
            ),
        ],
        [
            'type' => 'quizz',
            'handle' => 'quizz_vaudreuil',
            'organisation' => 'vaudreuil',
            'fields' => array(
                'name' => array(
                    'fr' => 'Mon quiz',
                    'en' => 'Mon quiz',
                ),

                'theme' => [
                    'color_light' => '#71BDC4', //Button, highlight
                    'color_medium' => '#173d42', //Details modal background
                    'color_normal' => '#1C494F', //Details view background
                    'color_dark' => '#153135', //Background
                    'color_darker' => '#0E2224', //Modal background
                    'color_shadow' => '#3D7C84', //Shadow button et button inactive
                    'color_shadow_darker' => '#3D7C84', //Shadow button details
                ],

                'settings' => [
                    'channelView' => 'filters_cards',
                    'channelFilterName' => 'question_category',
                    'colorPalette' => 'vaudreuil',
                    'canAddBubbles' => true,

                    // 'slideshowInfosView' => 'with_question',
                    'slideshowInfosView' => null,
                    'slideshowImageMaxWidth' => 0.3,

                    'slidesHeightRatio' => 0.48,
                    'slidesMarginRatio' => 0.04,
                    'slidesWidthRatio' => 0.4,
                    // 'slidesSlideView' => 'card',
                    'slidesSlideView' => 'with_basic_infos',

                    'bubbleDetailsExcludedButtons' => ['send-bubble'],
                    'bubbleDetailsShowTypeName' => false,
                    'bubbleDetailsShowTitle' => false,
                    'bubbleSuggestionView' => null,
                    'bubbleDetailsContentView' => 'question',
                ],

                'icon' => 'seeders/channels/banq-quizz.svg',
            ),
        ],
        [
            'type' => 'announcements',
            'handle' => 'announcements_vaudreuil',
            'organisation' => 'vaudreuil',
            'fields' => array(
                'name' => array(
                    'fr' => 'Mon journal',
                    'en' => 'Mon journal',
                ),

                'theme' => [
                    'color_light' => '#C61D22', //Button, highlight
                    'color_medium' => '#391316', //Details modal background
                    'color_normal' => '#6A191D', //Details view background
                    'color_dark' => '#43171A', //Background
                    'color_darker' => '#311113', //Modal background
                    'color_shadow' => '#99131D', //Shadow button et button inactive
                    'color_shadow_darker' => '#550F13', //Shadow button details
                ],

                'settings' => [
                    'channelView' => 'bubbles_cards',
                    'randomPositionCards' => false,
                    'canAddBubbles' => true,
                    'slideMenuDestinationView' => 'channel:main',
                ],

                'icon' => 'seeders/channels/events.svg',
            ),
        ],
        [
            'type' => 'locations',
            'handle' => 'locations_vaudreuil',
            'organisation' => 'vaudreuil',
            'fields' => array(
                'name' => array(
                    'fr' => 'Mes lieux',
                    'en' => 'Mes lieux',
                ),

                'theme' => [
                    'color_light' => '#C61D22', //Button, highlight
                    'color_medium' => '#391316', //Details modal background
                    'color_normal' => '#6A191D', //Details view background
                    'color_dark' => '#43171A', //Background
                    'color_darker' => '#311113', //Modal background
                    'color_shadow' => '#99131D', //Shadow button et button inactive
                    'color_shadow_darker' => '#550F13', //Shadow button details
                ],

                'settings' => [
                    'canAddBubbles' => true,
                    'channelView' => 'bubbles_map',
                    'channelMarkerType' => 'icon',

                    'bubbleDetailsContentView' => 'columns',
                    'bubbleDetailsContentColumns' => [
                        [
                            'column' => 0,
                            'type' => 'title',
                            'value' => 'snippet.title',
                        ],
                        [
                            'column' => 0,
                            'type' => 'description',
                            'value' => 'snippet.description',
                        ],
                        [
                            'column' => 1,
                            'type' => 'location',
                            'value' => 'fields.location',
                        ],
                        [
                            'column' => 1,
                            'type' => 'buttons',
                            'value' => 'send-bubble',
                        ],
                    ],
                ],

                'icon' => 'seeders/channels/banq-books.svg',
            ),
        ],
        [
            'type' => 'events',
            'handle' => 'events_vaudreuil',
            'organisation' => 'vaudreuil',
            'fields' => array(
                'name' => array(
                    'fr' => 'Mon agenda',
                    'en' => 'Mon agenda',
                ),

                'theme' => [
                    'color_light' => '#C61D22', //Button, highlight
                    'color_medium' => '#391316', //Details modal background
                    'color_normal' => '#6A191D', //Details view background
                    'color_dark' => '#43171A', //Background
                    'color_darker' => '#391316', //Modal background
                    'color_shadow' => '#99131D', //Shadow button et button inactive
                    'color_shadow_darker' => '#550F13', //Shadow button details
                ],

                'settings' => [
                    'canAddBubbles' => true,
                    'channelView' => 'filters_tabs',

                    'slidesHeightRatio' => 0.5,
                    'slidesMarginRatio' => 0.03,
                    'slidesWidthRatio' => 0.6,
                    'slidesSlideView' => 'with_basic_infos',

                    'modalBubblesListView' => 'events',

                    'bubbleSuggestionView' => 'with_dates',
                    'bubbleDetailsShowTypeName' => false,
                    'bubbleDetailsShowTitle' => false,
                    'bubbleDetailsContentView' => 'columns',
                    'bubbleDetailsContentColumns' => [
                        [
                            'column' => 0,
                            'type' => 'title',
                            'value' => 'fields.category.value',
                        ],
                        [
                            'column' => 0,
                            'type' => 'description',
                            'value' => 'snippet.description',
                        ],
                        [
                            'column' => 1,
                            'type' => 'fields',
                            'value' => 'fields.date,fields.venue,fields.room,fields.category',
                        ],
                        [
                            'column' => 1,
                            'type' => 'buttons',
                            'value' => 'send-bubble',
                        ],
                    ],
                ],

                'icon' => 'seeders/channels/events.svg',
            ),
        ],
        [
            'type' => 'announcements',
            'handle' => 'announcements',
            'fields' => array(
                'name' => array(
                    'fr' => 'Annonces',
                    'en' => 'Annonces',
                ),

                'theme' => [
                    'color_light' => '#C61D22', //Button, highlight
                    'color_medium' => '#391316', //Details modal background
                    'color_normal' => '#6A191D', //Details view background
                    'color_dark' => '#43171A', //Background
                    'color_darker' => '#311113', //Modal background
                    'color_shadow' => '#99131D', //Shadow button et button inactive
                    'color_shadow_darker' => '#550F13', //Shadow button details
                ],

                'settings' => [
                    'channelView' => 'bubbles_cards',
                    'randomPositionCards' => false,
                    'canAddBubbles' => true,
                    'bubblesByOrganisation' => true,
                    'slideMenuDestinationView' => 'channel:main',
                ],

                'icon' => 'seeders/channels/events.svg',
            ),
        ],
        [
            'type' => 'events',
            'handle' => 'activities',
            'fields' => array(
                'name' => array(
                    'fr' => 'Activités',
                    'en' => 'Activities',
                ),

                'theme' => [
                    'color_light' => '#C61D22', //Button, highlight
                    'color_medium' => '#391316', //Details modal background
                    'color_normal' => '#6A191D', //Details view background
                    'color_dark' => '#43171A', //Background
                    'color_darker' => '#391316', //Modal background
                    'color_shadow' => '#99131D', //Shadow button et button inactive
                    'color_shadow_darker' => '#550F13', //Shadow button details
                ],

                'settings' => [
                    'canAddBubbles' => true,
                    'bubblesByOrganisation' => true,
                    'channelView' => 'filters_tabs',

                    'slidesHeightRatio' => 0.5,
                    'slidesMarginRatio' => 0.03,
                    'slidesWidthRatio' => 0.6,
                    'slidesSlideView' => 'with_basic_infos',

                    'modalBubblesListView' => 'events',

                    'bubbleSuggestionView' => 'with_dates',
                    'bubbleDetailsShowTypeName' => false,
                    'bubbleDetailsShowTitle' => false,
                    'bubbleDetailsContentView' => 'columns',
                    'bubbleDetailsContentColumns' => [
                        [
                            'column' => 0,
                            'type' => 'title',
                            'value' => 'fields.category.value',
                        ],
                        [
                            'column' => 0,
                            'type' => 'description',
                            'value' => 'snippet.description',
                        ],
                        [
                            'column' => 1,
                            'type' => 'fields',
                            'value' => 'fields.date,fields.venue,fields.room,fields.category',
                        ],
                        [
                            'column' => 1,
                            'type' => 'buttons',
                            'value' => 'send-bubble',
                        ],
                    ],
                ],

                'icon' => 'seeders/channels/events.svg',
            ),
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::reguard();

        $resource = Manivelle::resource('channels');

        //Sync screens
        foreach ($this->items as $data) {
            try {
                $model = $resource->find([
                    'handle' => $data['handle'],
                ]);
            } catch (\Exception $e) {
                $model = null;
            }

            $icon = array_get($data, 'fields.icon');
            if ($icon) {
                array_set($data, 'fields.icon', storage_path($icon));
            }

            $organisation = array_get($data, 'organisation', null);
            if (
                $organisation &&
                ($organisation = Organisation::where('slug', $organisation)->first())
            ) {
                array_set($data, 'organisation_id', $organisation->id);
            }
            array_pull($data, 'organisation');

            if (!$model) {
                $model = $resource->store($data);
            } else {
                $model = $resource->update($model->id, $data);
            }
        }
    }
}
