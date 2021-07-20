<?php namespace Manivelle\Panneau\ItemsList;

use Panneau;
use Panneau\Support\ItemsList;

class OrganisationScreensList extends ItemsList
{
    protected $attributes = array(
        'name' => 'organisation.screens',
        'type' => 'screens'
    );

    protected $organisation;

    public function withOrganisation($organisation)
    {
        $this->organisation = $organisation;

        return $this;
    }

    public function render()
    {
        $contents = parent::render();

        $request = app('request');
        $createForm = $this->canCreate ? Panneau::form('screen.create')->withRequest($request)->renderSchema() : '';

        return $contents.$createForm;
    }
}
