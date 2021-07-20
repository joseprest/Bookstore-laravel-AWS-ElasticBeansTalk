<?php

namespace Manivelle\Services;

use Google_Client;
use Google_Service_Analytics;

class GoogleAnalytics
{
    protected $analytics;

    public function __construct()
    {
        $keyPath = storage_path('Manivelle-aeb2a1e722e0.json');

        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setApplicationName('Manivelle Analytics');
        $client->setAuthConfig($keyPath);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $this->analytics = new Google_Service_Analytics($client);
    }

    public function getManivelleAccount()
    {
        $accounts = $this->analytics->management_accounts->listManagementAccounts();
        $items = $accounts->getItems();
        $manivelleAccount = null;
        foreach ($items as $item) {
            if ($item->getName() === 'Manivelle') {
                $manivelleAccount = $item;
                break;
            }
        }

        return $manivelleAccount;
    }

    public function getScreenProperty($manivelleAccount = null)
    {
        $manivelleAccount = $manivelleAccount ? $manivelleAccount:$this->getManivelleAccount();

        if (!$manivelleAccount) {
            throw new \Exception('No Analytics account found for Manivelle');
        }

        $accountId = $manivelleAccount->getId();
        $properties = $this->analytics->management_webproperties->listManagementWebproperties($accountId);
        $screenProperty = null;
        foreach ($properties as $property) {
            if ($property->getName() === 'ecrans.manivelle.io') {
                $screenProperty = $property;
                break;
            }
        }

        return $screenProperty;
    }

    public function getScreenProfile($manivelleAccount = null, $screenProperty = null)
    {
        $manivelleAccount = $manivelleAccount ? $manivelleAccount:$this->getManivelleAccount();

        if (!$manivelleAccount) {
            throw new \Exception('No Analytics account found for Manivelle');
        }

        $screenProperty = $screenProperty ? $screenProperty:$this->getScreenProperty($manivelleAccount);

        if (!$screenProperty) {
            throw new \Exception('No Analytics property found for Manivelle screens');
        }

        $accountId = $manivelleAccount->getId();
        $propertyId = $screenProperty->getId();
        $profiles = $this->analytics->management_profiles->listManagementProfiles($accountId, $propertyId)->getItems();

        return sizeof($profiles) ? $profiles[0]:null;
    }

    protected function getScreenData($screen, $startDate, $endDate, $metrics, $query = [])
    {
        $screenProfile = $this->getScreenProfile();

        if (!$screenProfile) {
            throw new \Exception('No Analytics profile found for Manivelle Screens');
        }

        $profileId = $screenProfile->getId();

        return $this->analytics->data_ga->get('ga:'.$profileId, $startDate, $endDate, $metrics, $query);
    }

    public function getScreenSummary($screen, $startDate = '7daysAgo', $endDate = 'today')
    {
        $metrics = [
            'sessions' => 'ga:sessions',
            'pageviews' => 'ga:pageviews',
            'duration' => 'ga:avgSessionDuration'
        ];
        $results = $this->getScreenData($screen, $startDate, $endDate, implode(',', array_values($metrics)), [
            'filters' => 'ga:pagePath=~^/screen/'.$screen->id.'/'
        ]);
        $rows = $results->getTotalResults() > 0 ? $results->getRows():[];

        $stats = [];
        $i = 0;
        foreach ($metrics as $key => $value) {
            $stats[$key] = array_get($rows, '0.'.$i, 0);
            $i++;
        }

        $stats['events'] = $this->getScreenTotalEvents($screen, $startDate, $endDate);

        return $stats;
    }

    public function getScreenTotalEvents($screen, $startDate = '7daysAgo', $endDate = 'today')
    {
        $results = $this->getScreenData($screen, $startDate, $endDate, 'ga:totalEvents', [
            'filters' => 'ga:eventCategory=='.preg_quote('Screen #'.$screen->id)
        ]);
        $rows = $results->getTotalResults() > 0 ? $results->getRows():[];

        return array_get($rows, '0.0', 0);
    }

    public function getScreenEvents($screen, $startDate = '7daysAgo', $endDate = 'today')
    {
        $results = $this->getScreenData($screen, $startDate, $endDate, 'ga:totalEvents', [
            'dimensions' => 'ga:eventAction',
            'filters' => 'ga:eventCategory=='.preg_quote('Screen #'.$screen->id)
        ]);
        $rows = $results->getTotalResults() > 0 ? $results->getRows():[];

        $items = [];
        foreach ($rows as $row) {
            $items[$row[0]] = $row[1];
        }

        return $items;
    }

    public function getScreenPageviews($screen, $startDate = '7daysAgo', $endDate = 'today')
    {
        $results = $this->getScreenData($screen, $startDate, $endDate, 'ga:pageviews', [
            'dimensions' => 'ga:pagePath',
            'filters' => 'ga:pagePath=~^/screen/'.$screen->id.'/'
        ]);
        $rows = $results->getTotalResults() > 0 ? $results->getRows():[];

        $items = [];
        foreach ($rows as $row) {
            $items[$row[0]] = $row[1];
        }

        return $items;
    }
}
