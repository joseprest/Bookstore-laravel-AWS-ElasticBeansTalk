<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form;
use Localizer;
use Log;

class ChannelFiltersForm extends Form
{
    protected $attributes = [
        'name' => 'channel.filters'
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
        $channelType = $this->model->getChannelType();
        $filters = $channelType->getBubblesFilters();
        
        return [
            [
                'name' => 'filters',
                'type' => 'filters',
                'filters' => $filters
            ]
        ];
    }
    
    public function buttons()
    {
        return [];
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
