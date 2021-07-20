<?php namespace Manivelle\Http\Controllers\Organisation;

use Manivelle\Models\Organisation;

use Manivelle\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Panneau\Http\Requests\ResourceFormRequest;
use Panneau\Exceptions\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class ResourceController extends Controller
{
    protected $resource = '';
    
    protected $itemsLists = array();
    protected $views = array();
    protected $forms = array();
    
    protected $defaultForms = array(
        'create' => '\Panneau\Support\Form',
        'edit' => '\Panneau\Support\Form'
    );
    
    protected $defaultItemsLists = array(
        'index' => '\Panneau\Support\ItemsLists'
    );
    
    protected $defaultViews = array(
        'index' => 'panneau::list',
        'show' => 'panneau::form',
        'create' => 'panneau::form',
        'edit' => 'panneau::form'
    );
    
    public function __construct()
    {
        $this->middleware('panneau.middleware.auth');
        
        $this->resource = app('panneau')->resource($this->resource);
    }
    
    public function index(Request $request, Organisation $organisation)
    {
        $items = $this->getItems($request);
        
        $list = $this->listIndex()
                        ->setItems($items);
        
        return $this->viewIndex(array(
            'list' => $list
        ));
    }
    
    public function show(Request $request, Organisation $organisation, $id)
    {
        $item = $this->getItem($id);
        
        return $this->viewShow(array(
            'item' => $item
        ));
    }
    
    public function create(Request $request, Organisation $organisation)
    {
        $form = $this->formEdit()
                        ->setRequest($request);
        
        return $this->viewCreate(array(
            'form' => $form
        ));
    }
    
    public function edit(Request $request, Organisation $organisation, $id)
    {
        $item = $this->getItem($id);
        
        $form = $this->formEdit()
                        ->setModel($item)
                        ->setRequest($request);
        
        return $this->viewEdit(array(
            'form' => $form,
            'model' => $item
        ));
    }
    
    public function store(Request $request, Organisation $organisation)
    {
        //Form
        $form = $this->formCreate()
                    ->setRequest($request);
        
        //Validation
        $rules = $form->getRules();
        if ($rules && sizeof($rules)) {
            $this->validate($request, $rules);
        }
        
        $data = $request->all();
        $this->resource->store($data);
        
        return redirect()->action('\\'.get_class($this).'@index', array($organisation->slug));
    }
    
    public function update(Request $request, Organisation $organisation, $id)
    {
        $item = $this->getItem($id);
        
        //Form
        $form = $this->formEdit()
                        ->setModel($item)
                        ->setRequest($request);
        
        //Validation
        $rules = $form->getRules();
        if ($rules && sizeof($rules)) {
            $this->validate($request, $rules);
        }
        
        $data = $request->all();
        $this->resource->update($id, $data);
        
        return redirect()->action('\\'.get_class($this).'@index', array($organisation->slug));
    }
    
    public function destroy(Organisation $organisation, $id)
    {
        try {
            $this->resource->destroy($id);
        } catch (ResourceNotFoundException $e) {
            return abort(404);
        } catch (\Exception $e) {
            return abort(500);
        }
        
        return redirect()->action('\\'.get_class($this).'@index', array($organisation->slug));
    }
    
    protected function getItems(Request $request)
    {
        return $this->resource->get();
    }
    
    protected function getItem($id)
    {
        try {
            return $this->resource->find($id);
        } catch (ResourceNotFoundException $e) {
            throw new NotFoundHttpException('Resource not found.');
        }
    }
    
    public function getForms()
    {
        return array_merge($this->defaultForms, $this->forms, $this->forms());
    }
    
    public function getViews()
    {
        return array_merge($this->defaultViews, $this->views, $this->views());
    }
    
    public function getItemsLists()
    {
        return array_merge($this->defaultItemsLists, $this->itemsLists, $this->itemsLists());
    }
    
    public function forms()
    {
        return [];
    }
    
    public function views()
    {
        return [];
    }
    
    public function itemsLists()
    {
        return [];
    }
    
    protected function formCreate()
    {
        $forms = $this->getForms();
        return app('panneau')->form($forms['create']);
    }
    
    protected function formEdit()
    {
        $forms = $this->getForms();
        return app('panneau')->form($forms['edit']);
    }
    
    protected function listIndex()
    {
        $lists = $this->getItemsLists();
        return app('panneau')->itemsList($lists['index']);
    }
    
    protected function viewIndex($data)
    {
        $views = $this->getViews();
        return view($views['index'], $data);
    }
    
    protected function viewShow($data)
    {
        $views = $this->getViews();
        return view($views['show'], $data);
    }
    
    protected function viewCreate($data)
    {
        $views = $this->getViews();
        return view($views['create'], $data);
    }
    
    protected function viewEdit($data)
    {
        $views = $this->getViews();
        return view($views['edit'], $data);
    }
}
