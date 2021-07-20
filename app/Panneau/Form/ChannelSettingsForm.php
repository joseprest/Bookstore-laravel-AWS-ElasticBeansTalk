<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form;
use Localizer;
use Log;

class ChannelSettingsForm extends Form
{
    protected $attributes = [
        'name' => 'channel.settings'
    ];
    
    public function attributes()
    {
        $organisation = $this->request->route('organisation');
        $screenId = $this->request->route('screens');
        
        return [
            'action' => route(Localizer::routeName('organisation.screens.channel.update'), array($organisation->slug, $screenId, $this->model->id)),
            'method' => 'POST'
        ];
    }
    
    public function fields()
    {
        $fields = $this->model->getFields();
        $settingsFields = $fields->filter(function ($item) {
            return $item->settings && $item->settings === true;
        });
        return [
            [
                'type' => 'fieldset',
                'namespace' => 'screen_settings',
                'children' => $settingsFields->toArray()
            ]
        ];
    }
    
    public function buttons()
    {
        return [
            [
                'className' => 'btn btn-default',
                'type' => 'submit',
                'label' => trans('general.actions.save')
            ]
        ];
    }
    
    public function render()
    {
        try {
            return parent::render();
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }
}
