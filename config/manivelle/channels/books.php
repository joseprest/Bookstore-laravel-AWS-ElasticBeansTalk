<?php



return [

    'version' => env('CHANNELS_BOOKS_VERSION', '2'),

    'sync'=> [
        'libraries' => [
            'banq',
            'brossard',
            'quebec'
        ]
    ],

    'libraries' => [

        [
            'key' => 'chum',
            'title' => 'CHUM',
            'city' => 'Montréal',
        ],

        [
            'key' => 'banq',
            'title' => 'Bibliothèque et Archives nationales du Québec',
            'city' => 'Montréal',
        ],

        [
            'key' => 'abitibi-temiscamingue-nord-du-quebec',
            'title' => 'Réseau BIBLIO Abitibi-Témiscamingue-Nord-du-Québec',
            'city' => 'Abitibi-Témiscamingue-Nord-du-Québec',
        ],

        [
            'key' => 'reseaubibliobsl',
            'title' => 'Réseau BIBLIO Bas-Saint-Laurent',
            'city' => 'Bas-Saint-Laurent',
        ],

        [
            'key' => 'reseaubibliocnca',
            'title' => 'Réseau BIBLIO Capitale-Nationale-Chaudière-Appalaches',
            'city' => 'Capitale-Nationale et Chaudière-Appalaches',
        ],

        [
            'key' => 'reseaubibliocn',
            'title' => 'Réseau BIBLIO Côte-Nord',
            'city' => 'Côte-Nord',
        ],

        [
            'key' => 'reseaubiblioestrie',
            'title' => 'Réseau BIBLIO de l’Estrie',
            'city' => 'Estrie',
        ],

        [
            'key' => 'reseaubibliogim',
            'title' => 'Réseau BIBLIO de la Gaspésie–Îles-de-la-Madeleine',
            'city' => 'Gaspésie et Îles-de-la-Madeleine',
        ],

        [
            'key' => 'laurentides',
            'title' => 'Ma BIBLIO à moi',
            'city' => 'Laurentides',
        ],

        [
            'key' => 'rbm',
            'title' => 'Réseau BIBLIO de la Montérégie',
            'city' => 'Montérégie',
        ],

        [
            'key' => 'reseaubiblio-outaouais',
            'title' => 'Réseau BIBLIO de l\'Outaouais',
            'city' => 'Outaouais',
        ],

        [
            'key' => 'saguenay-lac-st-jean',
            'title' => 'Réseau BIBLIO Saguenay-Lac-St-Jean',
            'city' => 'Saguenay-Lac-St-Jean',
        ],

        [
            'key' => 'alma',
            'title' => 'Bibliothèque d\'Alma',
            'city' => 'Alma',
        ],

        [
            'key' => 'alice-lane',
            'title' => 'Bibliothèque Alice-Lane (Baie-Comeau)',
            'city' => 'Baie-Comeau',
        ],

        [
            'key' => 'beaconsfield',
            'title' => 'Bibliothèque de Beaconsfield',
            'city' => 'Beaconsfield',
        ],

        [
            'key' => 'beauharnois',
            'title' => 'Bibliothèque de Beauharnois',
            'city' => 'Beauharnois',
        ],

        [
            'key' => 'blainville',
            'title' => 'Bibliothèque de Blainville',
            'city' => 'Blainville',
        ],

        [
            'key' => 'boisbriand',
            'title' => 'Bibliothèque de Boisbriand',
            'city' => 'Boisbriand',
        ],

        [
            'key' => 'montarville-boucher-delabruere',
            'title' => 'Bibliothèque Montarville-Boucher-De La Bruère (Boucherville)',
            'city' => 'Boucherville',
        ],

        [
            'key' => 'brossard',
            'title' => 'Bibliothèque de Brossard',
            'city' => 'Brossard',
        ],

        [
            'key' => 'becancour',
            'title' => 'Bibliothèque de Bécancour',
            'city' => 'Bécancour',
        ],

        [
            'key' => 'candiac',
            'title' => 'Bibliothèque de Candiac',
            'city' => 'Candiac',
        ],

        [
            'key' => 'saguenay',
            'title' => 'Bibliothèques de Saguenay',
            'city' => 'Chicoutimi',
        ],

        [
            'key' => 'chateauguay',
            'title' => 'Bibliothèque municipale de Châteauguay',
            'city' => 'Châteauguay, a/s Bibliothèque municipale',
        ],

        [
            'key' => 'cowansville',
            'title' => 'Bibliothèque Gabrielle-Giroux-Bertrand (Cowansville)',
            'city' => 'Cowansville',
        ],

        [
            'key' => 'cote-saint-luc',
            'title' => 'Bibliothèque Eleanor London Côte Saint-Luc Public Library',
            'city' => 'Côte Saint-Luc',
        ],

        [
            'key' => 'deux-montagnes',
            'title' => 'Bibliothèque de Deux-Montagnes',
            'city' => 'Deux-Montagnes',
        ],

        [
            'key' => 'dolbeau-mistassini',
            'title' => 'Bibliothèque de Dolbeau-Mistassini',
            'city' => 'Dolbeau-Mistassini',
        ],

        [
            'key' => 'dollard-des-ormeaux',
            'title' => 'Bibliothèque publique de Dollard-des-Ormeaux',
            'city' => 'Dollard-des-Ormeaux',
        ],

        [
            'key' => 'dorval',
            'title' => 'Bibliothèque de Dorval',
            'city' => 'Dorval',
        ],

        [
            'key' => 'drummondville',
            'title' => 'Bibliothèque publique de Drummondville',
            'city' => 'Drummondville',
        ],

        [
            'key' => 'fermont',
            'title' => 'Bibliothèque Publique de Fermont',
            'city' => 'Fermont',
        ],

        [
            'key' => 'nouveau-brunswick',
            'title' => 'Bibliothèques publiques du Nouveau-Brunswick',
            'city' => 'Fredericton',
        ],

        [
            'key' => 'gatineau',
            'title' => 'Bibliothèque municipale de Gatineau',
            'city' => 'Gatineau',
        ],

        [
            'key' => 'granby',
            'title' => 'Bibliothèque Paul-O.-Trépanier (Granby)',
            'city' => 'Granby',
        ],

        [
            'key' => 'lassomption',
            'title' => 'Bibliothèque Christian-Roy de L\'Assomption',
            'city' => 'L\'Assomption',
        ],

        [
            'key' => 'guy-godin',
            'title' => 'Bibliothèque Guy-Godin (L\'Île-Perrot)',
            'city' => 'L\'Île-Perrot',
        ],

        [
            'key' => 'laprairie',
            'title' => 'Bibliothèque Léo-Lecavalier de La Prairie',
            'city' => 'La Prairie',
        ],

        [
            'key' => 'lasarre',
            'title' => 'Bibliothèque municipale Richelieu de La Sarre',
            'city' => 'La Sarre',
        ],

        [
            'key' => 'jean-marc-belzile',
            'title' => 'Bibliothèque Jean-Marc-Belzile de Lachute',
            'city' => 'Lachute',
        ],

        [
            'key' => 'longueuil',
            'title' => 'Réseau des bibliothèques publiques de Longueuil',
            'city' => 'Longueuil',
        ],

        [
            'key' => 'lorraine',
            'title' => 'Bibliothèque de Lorraine',
            'city' => 'Lorraine',
        ],

        [
            'key' => 'magog',
            'title' => 'Bibliothèque Memphrémagog',
            'city' => 'Magog',
        ],

        [
            'key' => 'marieville',
            'title' => 'Bibliothèque de Marieville',
            'city' => 'Marieville',
        ],

        [
            'key' => 'matane',
            'title' => 'Bibliothèque Municipale Fonds de solidarité FTQ',
            'city' => 'Matane',
        ],

        [
            'key' => 'bibliomercier',
            'title' => 'Bibliothèque de Mercier',
            'city' => 'Mercier',
        ],

        [
            'key' => 'mont-joli',
            'title' => 'Bibliothèque Jean-Louis-Desrosiers de Mont-Joli',
            'city' => 'Mont-Joli',
        ],

        [
            'key' => 'reginald-jp-dawson',
            'title' => 'Bibliothèque Reginald-J.-P.-Dawson (Ville de Mont-Royal)',
            'city' => 'Mont-Royal',
        ],

        [
            'key' => 'mont-saint-hilaire',
            'title' => 'Bibliothèque Armand-Cardinal Mont-Saint-Hilaire',
            'city' => 'Mont-Saint-Hilaire',
        ],

        [
            'key' => 'montmagny',
            'title' => 'Bibliothèque de Montmagny',
            'city' => 'Montmagny',
        ],

        [
            'key' => 'montreal',
            'title' => 'Bibliothèques de Montréal',
            'city' => 'Montréal',
        ],

        [
            'key' => 'plessisville',
            'title' => 'Bibliothèque Linette-Jutras-Laperle',
            'city' => 'Plessisville',
        ],

        [
            'key' => 'prevost',
            'title' => 'Bibliothèque de Prévost',
            'city' => 'Prévost',
        ],

        [
            'key' => 'quebec',
            'title' => 'Bibliothèque de Québec',
            'city' => 'Québec',
        ],

        [
            'key' => 'repentigny',
            'title' => 'Réseau des bibliothèques de Repentigny',
            'city' => 'Repentigny',
        ],

        [
            'key' => 'rimouski',
            'title' => 'Bibliothèque Lisette-Morin de Rimouski',
            'city' => 'Rimouski',
        ],

        [
            'key' => 'riviere-du-loup',
            'title' => 'Bibliothèque municipale Françoise-Bédard de Rivière-du-Loup',
            'city' => 'Rivière-du-Loup',
        ],

        [
            'key' => 'saint-augustin-de-desmaures',
            'title' => 'Bibliothèque Alain-Grandbois Saint-Augustin-de-Desmaures',
            'city' => 'Saint-Augustin-de-Desmaures',
        ],

        [
            'key' => 'saint-basile-le-grand',
            'title' => 'Bibliothèque Roland-LeBlanc, Saint-Basile-le-Grand',
            'city' => 'Saint-Basile-le-Grand',
        ],

        [
            'key' => 'saint-colomban',
            'title' => 'Bibliothèque de Saint-Colomban',
            'city' => 'Saint-Colomban',
        ],

        [
            'key' => 'saint-eustache',
            'title' => 'Bibliothèque de Saint-Eustache',
            'city' => 'Saint-Eustache',
        ],

        [
            'key' => 'saintgeorges',
            'title' => 'Bibliothèque municipale de Saint-Georges',
            'city' => 'Saint-Georges',
        ],

        [
            'key' => 'maskoutaine',
            'title' => 'Médiathèque maskoutaine',
            'city' => 'Saint-Hyacinthe',
        ],

        [
            'key' => 'saint-jean-sur-richelieu',
            'title' => 'Bibliothèque municipale de Saint-Jean-sur-Richelieu',
            'city' => 'Saint-Jean-sur-Richelieu',
        ],

        [
            'key' => 'saint-lambert',
            'title' => 'Bibliothèque de Saint-Lambert',
            'city' => 'Saint-Lambert',
        ],

        [
            'key' => 'sainte-catherine',
            'title' => 'Bibliothèque de Ville de Sainte-Catherine',
            'city' => 'Sainte-Catherine',
        ],

        [
            'key' => 'ste-julie',
            'title' => 'Bibliothèque municipale de Sainte-Julie',
            'city' => 'Sainte-Julie',
        ],

        [
            'key' => 'sainte-marie',
            'title' => 'Bibliothèque Honorius-Provost de Sainte-Marie',
            'city' => 'Sainte-Marie',
        ],

        [
            'key' => 'sainte-therese',
            'title' => 'Bibliothèque de Sainte-Thérèse',
            'city' => 'Sainte-Thérèse',
        ],

        [
            'key' => 'biblio-armand-frappier',
            'title' => 'Bibliothèque Armand-Frappier de Salaberry-de-Valleyfield',
            'city' => 'Salaberry-de-Valleyfield',
        ],

        [
            'key' => 'shawinigan',
            'title' => 'Bibliothèques de Shawinigan',
            'city' => 'Shawinigan',
        ],

        [
            'key' => 'terrebonne',
            'title' => 'Bibliothèque publique de Terrebonne',
            'city' => 'Terrebonne',
        ],

        [
            'key' => 'cqlm',
            'title' => 'Réseau BIBLIO du Centre du Québec Lanaudière et Mauricie',
            'city' => 'Trois-Rivières',
        ],

        [
            'key' => 'bibli3r',
            'title' => 'Service des bibliothèques de Trois-Rivières',
            'city' => 'Trois-Rivières',
        ],

        [
            'key' => 'yvonne-l-bombardier',
            'title' => 'Bibliothèque Yvonne-L.-Bombardier (Valcourt)',
            'city' => 'Valcourt',
        ],

        [
            'key' => 'jacques-lemoyne',
            'title' => 'Bibliothèque Jacques-Lemoyne-de-Sainte-Marie, Varennes',
            'city' => 'Varennes',
        ],

        [
            'key' => 'cegepvicto',
            'title' => 'Cégep de Victoriaville',
            'city' => 'Victoriaville',
        ],

        [
            'key' => 'laval',
            'title' => 'Bibliothèques de Laval',
            'city' => 'Ville de Laval',
        ],

        [
            'key' => 'westmount',
            'title' => 'Bibliothèque de Westmount / Westmount Public Library',
            'city' => 'Westmount',
        ],
    ]
];
