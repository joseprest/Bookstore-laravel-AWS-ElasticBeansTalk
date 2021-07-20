<?php namespace Manivelle\Panneau\ItemsList;

use Auth;
use Panneau;
use Panneau\Support\ItemsList;

class OrganisationTeamList extends ItemsList
{
    
    protected $attributes = array(
        'name' => 'organisation.team',
        'type' => 'team'
    );
    
    public function attributes()
    {
        return [
            'canAdd' => Auth::check() && (!Auth::user()->is('admin') || !Auth::user()->is('organisation.admin')),
            'canEdit' => Auth::check() && (!Auth::user()->is('admin') || !Auth::user()->is('organisation.admin'))
        ];
    }
    
    public function render()
    {
        $contents = parent::render();
        
        $request = app('request');
        $inviteForm = Panneau::form('team.invite')->withRequest($request);
        $editForm = Panneau::form('team.edit')->withRequest($request);
        $invitationForm = Panneau::form('team.invitation')->withRequest($request);
        
        return $contents.$inviteForm->renderSchema().$editForm->renderSchema().$invitationForm->renderSchema();
    }
}
