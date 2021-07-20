<?php namespace Manivelle\GraphQL\Field;

use GraphQL;
use App;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Field;

class TextLocaleField extends Field
{
    public function type()
    {
        return Type::string();
    }
    
    public function args()
    {
        return [
            
        ];
    }
    
    public function resolve($root, $args = [])
    {
        $name = $this->name;
        $locale = App::getLocale();
        if (!$root || !isset($root->{$name}) || !$root->{$name} || !isset($root->{$name}->{$locale})) {
            return null;
        }
        
        return $root->{$name}->{$locale};
    }
}
