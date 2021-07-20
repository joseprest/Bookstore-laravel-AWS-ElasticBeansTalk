<?php
// Exceptionnaly, we define an array here. This array is strictly
// to ease the understanding of the template code and has no
// logic, hence it is here instead of being inside a Composer

$defaultHeight = 20;

$priorityLogos = [
    [
        'img' => 'fmc.png',
        'url' => 'http://www.cmf-fmc.ca/',
        'height' => $defaultHeight + 10
    ]
];

$logos = [
    [
        'img' => 'biblio-mtl.png',
        'url' => 'http://ville.montreal.qc.ca/portal/page?_pageid=4276,6695558&_dad=portal&_schema=PORTAL',
        'height' => $defaultHeight + 20
    ],
    [
        'img' => 'bibliotech.png',
        'url' => 'http://www.bibliotech.education/'
    ],
    [
        'img' => 'ville-quebec.png',
        'url' => 'https://www.ville.quebec.qc.ca/',
        'height' => $defaultHeight + 30
    ],
    [
        'img' => 'brossard.png',
        'url' => 'http://www.ville.brossard.qc.ca/'
    ],
    [
        'img' => 'banq.png',
        'url' => 'http://www.banq.qc.ca/accueil/'
    ],
    [
        'img' => 'polytechnique-mtl.png',
        'url' => 'http://www.polymtl.ca/',
        'height' => $defaultHeight + 10
    ],
    [
        'img' => 'espaces-temps.png',
        'url' => 'http://espacestemps.ca/',
        'height' => $defaultHeight + 10
    ],
    [
        'img' => 'pme-mtl.png',
        'url' => 'https://pmemtl.com/',
        'height' => $defaultHeight + 20
    ],
    [
        'img' => 'futurpreneur.png',
        'url' => 'http://www.futurpreneur.ca/',
        'height' => $defaultHeight + 10
    ]
];
?>

<div class="partners">
    <h4 class="title">{{ trans('layout.footer.partners.text') }}</h4>
    <div class="partners-logos">
        @foreach ([$priorityLogos, $logos] as $logosCategory)
            <ul>
                @foreach ($logosCategory as $logo)
                <li class="partner-logo">
                    <a href="{{ htmlspecialchars($logo['url']) }}" target="_blank">
                        <img src="{{ asset('img/partners/' . $logo['img']) }}"
                            class="logo"
                            style="{{ isset($logo['height']) ? 'height:' . $logo['height'] . 'px' : '' }}">
                    </a>
                </li>
                @endforeach
            </ul>
        @endforeach
    </div>
</div>
