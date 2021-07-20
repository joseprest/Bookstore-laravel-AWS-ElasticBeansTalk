<?php namespace Manivelle\GraphQL\Field;

use GraphQL;
use App;
use GraphQL\Type\Definition\Type;

class ChannelTextLocaleField extends TextLocaleField
{
    public function resolve($root, $args = [])
    {
        $fields = $root->fields;
        $name = $this->name;
        $locale = App::getLocale();
        if (!$fields || !isset($fields->{$name}) || !$fields->{$name} || !isset($fields->{$name}->{$locale})) {
            return null;
        }
        
        return $fields->{$name}->{$locale};
    }
}
