<?php namespace Manivelle\View\Composers\Organisation;

use View;
use Panneau;
use Manivelle;
use Auth;
use Gate;

class IndexComposer
{
    public function compose($view)
    {
        $organisation = View::shared('currentOrganisation');
        $user = Auth::user();

        //Get screens
        $organisation->loadScreens();
        $screens = $organisation->screens;
        $screensList = Panneau::itemsList('organisation.screens')
                            ->with([
                                'canCreate' => $user->can('screenCreate', $organisation),
                            ])
                            ->withOrganisation($organisation)
                            ->setItems($screens);
        $view->screensList = $screensList;

        //Get team
        if ($user->can('teamManage', $organisation)) {
            $organisation->loadTeam();
            $team = $organisation->getTeam();
            $teamList = Panneau::itemsList('organisation.team')
                                ->setItems($team);
            $view->teamList = $teamList;
        }
    }
}
