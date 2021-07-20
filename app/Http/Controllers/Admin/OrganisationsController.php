<?php namespace Manivelle\Http\Controllers\Admin;

use Panneau\Http\Controllers\ResourceController;

use Illuminate\Http\Request;
use Auth;
use Manivelle\Models\Role;
use Localizer;

class OrganisationsController extends ResourceController
{
    protected $resource = 'organisations';
    
    protected $forms = array(
        'create' => 'organisation',
        'edit' => 'organisation'
    );
    
    protected $itemsLists = array(
        'index' => 'organisations'
    );
    
    protected $views = array(
        'index' => 'admin.organisations.index',
        'edit' => 'admin.organisations.form',
        'create' => 'admin.organisations.form'
    );
    
    public function store(Request $request)
    {
        //Create form
        $form = $this->formCreate()
                    ->setRequest($request);
        
        //Validation
        $rules = $form->getRules();
        if ($rules && sizeof($rules)) {
            $this->validate($request, $rules);
        }
        
        //Save
        $data = $request->all();
        $model = $this->resource->store($data);
        
        //Attach current user
        $model->attachUser(Auth::user(), Role::where('slug', 'organisation.admin')->first());
        
        if ($request->wantsJson()) {
            return $model;
        } else {
            return redirect()->route(Localizer::routeName('admin.organisations.index'));
        }
    }
}
