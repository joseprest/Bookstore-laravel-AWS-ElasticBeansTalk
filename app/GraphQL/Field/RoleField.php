<?php namespace Manivelle\GraphQL\Field;

use Image;
use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Field;

class RoleField extends Field
{
    public function type()
    {
        return GraphQL::type('Role');
    }

    public function args()
    {
        return [];
    }

    public function resolve($root, $args = [])
    {
        if (!isset($root->{$this->name})) {
            return null;
        }

        return $root->{$this->name};
    }
}
