<?php namespace Manivelle\Http\Controllers\Organisation;

use Panneau;
use Localizer;

use Manivelle\Models\Organisation;

use Illuminate\Http\Request;

use Manivelle\Http\Controllers\Controller;

class OrganisationController extends Controller
{
    public function index(Request $request, Organisation $organisation)
    {
        return view('organisation.index');
    }
    
    public function edit(Request $request, Organisation $organisation)
    {
        $this->authorize('edit', $organisation);
        
        $form = Panneau::form('organisation')
                        ->setModel($organisation)
                        ->setRequest($request);
        
        return view('organisation.edit', [
            'form' => $form
        ]);
    }
    
    public function update(Request $request, Organisation $organisation)
    {
        //Form
        $form = Panneau::form('organisation')
                        ->setModel($organisation)
                        ->setRequest($request);
        
        //Validation
        $rules = $form->getRules();
        if ($rules && sizeof($rules)) {
            $this->validate($request, $rules);
        }
        
        $data = $request->all();
        $updatedOrganisation = Panneau::resource('organisations')->update($organisation->id, $data);
        
        return redirect()->route(Localizer::routeName('organisation.home'), [$updatedOrganisation->slug]);
    }
}
