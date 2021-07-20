<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form as BaseForm;
use Log;

class Form extends BaseForm
{
    protected $routeUpdate = 'route.update';
    protected $routeStore = 'route.store';
    
    protected $typeFields = null;
    
    public function attributes()
    {
        $url = app('url');
        if ($this->model && $this->model->id) {
            $action = $url->route($this->routeUpdate, array($this->model->id));
            $method = 'PUT';
        } else {
            $action = $url->route($this->routeStore);
            $method = 'POST';
        }
        
        return array(
            'action' => $action,
            'method' => $method
        );
    }
    
    public function getButtons()
    {
        return [
            [
                'type' => 'submit',
                'label' => trans('general.actions.save'),
                'className' => 'btn btn-primary'
            ]
        ];
    }
    
    protected function createModelType()
    {
        $type = $this->request->input('type', 'bubble');
        return app('panneau')->bubbleType($type);
    }
    
    protected function getModelTypeFields()
    {
        if (!$this->typeFields) {
            if ($this->model) {
                $this->typeFields = $this->model->getFields()->toArray();
            } else {
                $modelType = $this->createModelType();
                $this->typeFields = $modelType->getFields()->toArray();
            }
        }
        
        return $this->typeFields;
    }
    
    protected function addFieldset(&$fields, $fieldsetKey, $opts)
    {
        $subFields = $this->getFieldsForFieldset($fieldsetKey);
        if (sizeof($subFields)) {
            $fields[] = array_merge([
                'type' => 'fieldset',
                'children' => $subFields
            ], $opts);
        }
    }
    
    protected function getFieldsForFieldset($fieldset)
    {
        $fields = $this->getModelTypeFields();
        
        return array_where($fields, function ($key, $field) use ($fieldset) {
            if (isset($field['fieldset']) && $field['fieldset'] === $fieldset) {
                return true;
            }
            return false;
        });
    }
    
    public function __toString()
    {
        try {
            return parent::__toString();
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }
}
