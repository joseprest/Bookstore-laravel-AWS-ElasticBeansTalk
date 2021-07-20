<?php namespace Manivelle\Http\Controllers;

use Auth;
use Panneau;
use Localizer;

class HomeController extends Controller
{
    public function index()
    {
        if ($this->user) {
            if ($this->user->is('admin')) {
                return $this->admin();
            } else {
                return $this->user();
            }
        } else {
            return view('home.public');
        }
    }

    public function user()
    {
        $organisationsList = $this->getUserOrganisationsList();
        $items = $organisationsList->getItems();

        if (sizeof($items) === 1) {
            return redirect()->route(Localizer::routeName('organisation.home'), [$items[0]->slug]);
        }

        return view('home.user', array(
            'organisationsList' => $organisationsList
        ));
    }

    public function admin()
    {
        $organisationsList = $this->getUserOrganisationsList();
        if (sizeof($organisationsList->items()) === 1) {
            return redirect()->route(Localizer::routeName('organisation.home'), [$organisationsList->items[0]->slug]);
        }

        return view('home.admin', array(
            'organisationsList' => $organisationsList
        ));
    }

    protected function getUserOrganisationsList()
    {
        $organisations = $this->user->organisations->sortBy('name')->values()->all();
        $organisationsList = Panneau::itemsList('organisations')
                                        ->withItems($organisations);
        return $organisationsList;
    }
}
