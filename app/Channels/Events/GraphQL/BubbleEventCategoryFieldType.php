<?php namespace Manivelle\Channels\Events\GraphQL;

use GraphQL;
use GraphQL\Type\Definition\Type;

use Folklore\GraphQL\Support\Type as GraphQLType;

use Manivelle\GraphQl\Type\BubbleField\BubbleCategoryFieldType;

class BubbleEventCategoryFieldType extends BubbleCategoryFieldType
{
    protected $attributes = [
        'name' => 'BubbleEventCategoryField',
        'description' => 'A bubble field'
    ];
}
