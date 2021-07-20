<?php namespace Manivelle\Support;

use Manivelle;

use Panneau\Bubbles\Support\BubbleType as BaseBubbleType;

class ScreenType extends BaseBubbleType
{
    protected $defaultAttributes = array(
        'type' => 'screen'
    );
    
    public function fields()
    {
        $fields = [
            [
                'name' => 'technical',
                'type' => 'screen_technical'
            ],
            [
                'name' => 'location',
                'type' => 'location'
            ]
        ];
        
        return $fields;
    }
    
    public function snippet()
    {
        return [
            'title' => function ($fields, $model) {
                return $model->name;
            },
            'subtitle' => function ($fields, $model) {
                return null;
            },
            'description' => function ($fields, $model) {
                $resolutionX = isset($fields->technical->resolution->x) ? $fields->technical->resolution->x:0;
                $resolutionY = isset($fields->technical->resolution->y) ? $fields->technical->resolution->y:0;
                $size = isset($fields->technical->size) ? $fields->technical->size:null;
                if ($resolutionX > $resolutionY) {
                    $orientation = 'horizontal';
                } elseif ($resolutionX < $resolutionY) {
                    $orientation = 'vertical';
                } else {
                    $orientation = 'square';
                }

                $description = trans('screen.orientations.' . $orientation);
                if (!empty($size)) {
                    $description = trans(
                        'screen.descriptions.with_size',
                        [
                            'type' => $description,
                            'size' => $size
                        ]
                    );
                }

                return $description;
            },
            'picture' => function ($fields, $model) {
                return null;
            }
        ];
    }
}
