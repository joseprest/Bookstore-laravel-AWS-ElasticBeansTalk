<?php namespace Manivelle\View\Composers\Organisation;

use View;
use Manivelle\User;
use Panneau;
use Route;

class EditComposer
{
    public function compose($view)
    {
        $organisation = View::shared('currentOrganisation');
                            
        //Get team
        $teamList = Panneau::itemsList('organisation.team')
                            ->setItems($organisation->team);
        
        $view->teamList = $teamList;
    }
}
