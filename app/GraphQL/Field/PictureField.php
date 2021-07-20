<?php namespace Manivelle\GraphQL\Field;

use Image;
use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Field;

class PictureField extends Field
{
    public function type()
    {
        return GraphQL::type('Picture');
    }
    
    public function args()
    {
        return [
            'width' => [
                'type' => Type::int(),
                'description' => 'The width of the image'
            ],
            'height' => [
                'type' => Type::int(),
                'description' => 'The height of the image'
            ],
            'filter' => [
                'type' => Type::string(),
                'description' => 'The filter of the image resize'
            ],
            'crop' => [
                'type' => Type::string(),
                'description' => 'Crop the image'
            ]
        ];
    }
    
    public function resolve($root, $args = [])
    {
        $name = $this->name;
        if (!isset($root->{$name}) || !$root->{$name}) {
            return null;
        }
        $picture = $root->{$name};
        $width = $picture['width'];
        $height = $picture['height'];
        $url = null;
        if (isset($args['width']) || isset($args['height']) || isset($args['filter'])) {
            $opts = [];
            if (isset($args['width'])) {
                $opts['width'] = $args['width'];
            }
            if (isset($args['height'])) {
                $opts['height'] = $args['height'];
            }
            if (isset($args['filter'])) {
                $opts[] = $args['filter'];
            }
            if (isset($args['crop'])) {
                $opts['crop'] = true;
            }
            
            if (preg_match('/placehold\.it/', $picture['link'])) {
                $opts = array_merge(array(
                    'width' => $width,
                    'height' => $height
                ), $opts);
                $width = $opts['width'];
                $height = $opts['height'];
                $url = 'http://placehold.it/'.$width.'x'.$height;
            } else {
                $url = Image::url($picture['link'], $opts);
            }
        } elseif (!empty($picture['link'])) {
            $url = $picture['link'];
        }
        
        return $url ? [
            'width' => $width,
            'height' => $height,
            'link' => $url
        ]:null;
    }
}
