<?php namespace Manivelle\Panneau\Form;

use Localizer;

class BubbleForm extends Form
{
    protected $attributes = array(
        'name' => 'bubble'
    );

    public function attributes()
    {
        $organisation = request()->route('organisation');
        $screen = $this->attributes['screen'];
        $channel = $this->attributes['channel'];
        if ($this->model && $this->model->id) {
            $action = route(Localizer::routeName('organisation.bubbles.update'), array($organisation->slug, $screen->id, $channel->id, $this->model->id));
            $method = 'PUT';
        } else {
            $action = route(Localizer::routeName('organisation.bubbles.store'), [$organisation->slug, $screen->id, $channel->id]);
            $method = 'POST';
        }

        return array(
            'name' => 'bubble.'.$this->getBubbleType(),
            'action' => $action,
            'method' => $method
        );
    }

    public function fields()
    {
        $fields = $this->getModelTypeFields();
        $bubbleType = $this->getBubbleType();
        return [
            [
                'type' => 'hidden',
                'name' => 'type',
                'value' => $bubbleType
            ],
            [
                'type' => 'fieldset',
                'namespace' => 'fields',
                'children' => array_map(function ($field) {
                    $type = $field['type'];
                    if (array_get($field, 'hidden', false) === true) {
                        $type = 'hidden';
                    } elseif ($field['type'] === 'string') {
                        $type = 'text';
                    }
                    return array_merge($field, [
                        'type' => $type,
                    ]);
                }, $fields)
            ]
        ];
    }

    protected function getBubbleType()
    {
        if ($this->model) {
            return $this->model->type;
        } elseif (isset($this->attributes['channel'])) {
            return $this->attributes['channel']->getChannelType()->bubbleType;
        } elseif ($this->request) {
            return $this->request->input('type', 'bubble');
        } else {
            return array_get($this->attributes, 'type', 'bubble');
        }
    }

    protected function createModelType()
    {
        $type = $this->getBubbleType();
        return app('panneau')->bubbleType($type);
    }

    public function __toString()
    {
        try {
            return parent::__toString();
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
