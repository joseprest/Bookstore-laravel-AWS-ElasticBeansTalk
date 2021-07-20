<?php

namespace Manivelle\Listeners;

use Carbon\Carbon;
use App;

class LocaleEventListener
{
    public function handle()
    {
        $locale = App::getLocale();
        
        if ($locale === 'en') {
            setlocale(LC_TIME, 'en_CA', 'en_CA.UTF-8', 'en_CA.utf8', 'enc', 'en_US', 'en', 'english');
        } elseif ($locale === 'fr') {
            setlocale(LC_TIME, 'fr_CA.UTF-8', 'fr_CA.utf8', 'fr_CA', 'frc', 'fr_FR', 'fr', 'french', 'fr-CA');
        }
        
        Carbon::setLocale($locale);
    }
}
