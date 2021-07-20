<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Manivelle\Models\Organisation;

class SourcesSeeder extends Seeder
{
    protected $items = [
        [
            'handle' => 'pretnumerique',
            'type' => 'pretnumerique',
            'name' => 'Prêt Numérique (autres)',
            'settings' => [
                'libraries' => [
                    'saint-augustin-de-desmaures',
                    'saintcamille',
                    'sainte-catherine',
                    'alice-lane',
                ]
            ]
        ],
        [
            'handle' => 'pretnumerique_boisbriand',
            'type' => 'pretnumerique',
            'name' => 'Prêt Numérique (Boisbriand)',
            'settings' => [
                'libraries' => [
                    'boisbriand',
                ]
            ]
        ],
        [
            'handle' => 'pretnumerique_alma',
            'type' => 'pretnumerique',
            'name' => 'Prêt Numérique (Alma)',
            'settings' => [
                'libraries' => [
                    'alma',
                ]
            ]
        ],
        [
            'handle' => 'pretnumerique_riviereduloup',
            'type' => 'pretnumerique',
            'name' => 'Prêt Numérique (Rivière-du-loup)',
            'settings' => [
                'libraries' => [
                    'riviere-du-loup',
                ]
            ]
        ],
        [
            'handle' => 'pretnumerique_lachine',
            'type' => 'pretnumerique',
            'name' => 'Prêt Numérique (Lachine)',
            'settings' => [
                'libraries' => [
                    'lachine',
                ]
            ]
        ],
        [
            'handle' => 'pretnumerique_saintlambert',
            'type' => 'pretnumerique',
            'name' => 'Prêt Numérique (Saint-Lambert)',
            'settings' => [
                'libraries' => [
                    'saint-lambert',
                ]
            ]
        ],
        [
            'handle' => 'pretnumerique_montreal',
            'type' => 'pretnumerique',
            'name' => 'Prêt Numérique (Montréal)',
            'settings' => [
                'libraries' => [
                    'montreal',
                ]
            ]
        ],
        [
            'handle' => 'pretnumerique_longueuil',
            'type' => 'pretnumerique',
            'name' => 'Prêt Numérique (Longueuil)',
            'settings' => [
                'libraries' => [
                    'longueuil',
                ]
            ]
        ],
        [
            'handle' => 'pretnumerique_laval',
            'type' => 'pretnumerique',
            'name' => 'Prêt Numérique (Laval)',
            'settings' => [
                'libraries' => [
                    'laval',
                ]
            ]
        ],
        [
            'handle' => 'pretnumerique_banq',
            'type' => 'pretnumerique',
            'name' => 'Prêt Numérique (BANQ)',
            'settings' => [
                'libraries' => [
                    'banq',
                ]
            ]
        ],
        [
            'handle' => 'pretnumerique_brossard',
            'type' => 'pretnumerique',
            'name' => 'Prêt Numérique (Brossard)',
            'settings' => [
                'libraries' => [
                    'brossard',
                ]
            ]
        ],
        [
            'handle' => 'pretnumerique_quebec',
            'type' => 'pretnumerique',
            'name' => 'Prêt Numérique (Québec)',
            'settings' => [
                'libraries' => [
                    'quebec',
                ]
            ]
        ],
        [
            'handle' => 'pretnumerique_chum',
            'type' => 'pretnumerique',
            'name' => 'Prêt Numérique (CHUM)',
            'settings' => [
                'libraries' => [
                    'chum',
                ]
            ]
        ],
        [
            'handle' => 'murmitoyen',
            'type' => 'murmitoyen',
            'name' => 'Mur Mitoyen'
        ],
        [
            'handle' => 'banq_photos',
            'type' => 'banq_photos',
            'name' => 'BANQ - Photos'
        ],
        [
            'handle' => 'banq_cards',
            'type' => 'banq_cards',
            'name' => 'BANQ - Cartes Postales'
        ],
        [
            'handle' => 'banq_quizz',
            'type' => 'banq_quizz',
            'name' => 'BANQ - Le Saviez-Vous?'
        ],
        [
            'handle' => 'banq_books',
            'type' => 'banq_books',
            'name' => 'BANQ - Romans à Découvrir'
        ],
        [
            'handle' => 'cairn',
            'type' => 'cairn',
            'name' => 'CAIRN'
        ],
        [
            'handle' => 'quizz_csv',
            'type' => 'quizz_csv',
            'name' => 'Quizz CSV'
        ],
        [
            'handle' => 'mosaik_announcements',
            'type' => 'mosaik_announcements',
            'name' => 'Mosaik - Mon Journal'
        ],
        [
            'handle' => 'mosaik_events',
            'type' => 'mosaik_events',
            'name' => 'Mosaik - Mon Agenda'
        ],
        // [
        //     'handle' => 'mosaik_locations',
        //     'type' => 'mosaik_locations',
        //     'name' => 'Mosaik - Mes lieux'
        // ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resource = Manivelle::resource('sources');

        //Sync screens
        foreach ($this->items as $data) {
            try {
                $model = $resource->find([
                    'handle' => $data['handle']
                ]);
            } catch (\Exception $e) {
                $model = null;
            }

            if (!$model) {
                $model = $resource->store($data);
            } else {
                $model = $resource->update($model->id, $data);
            }
        }
    }
}
