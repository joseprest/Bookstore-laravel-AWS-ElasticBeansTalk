<?php namespace Manivelle\GraphQL\Type\ChannelFilterValue;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Manivelle\Support\Str;

class ChannelFilterValueListAlphabeticType extends ChannelFilterValueType
{
    public function attributes()
    {
        return [
            'name' => 'ChannelFilterValueListAlphabetic',
            'description' => 'Channel filter value'
        ];
    }
    
    public function fields()
    {
        $fields = parent::fields();
        
        $fields['alpha'] = [
            'type' => Type::string(),
            'description' => 'Alpha value',
            'resolve' => function ($item) {
                $name = $item['value'];
                $sort = array_get($item, 'filter.alphabetic_sort', 'default');
                if ($sort === 'name') {
                    if (preg_match('/^([^\s]+\s[A-Z]\.?\s)(.*)$/', $name, $matches)) {
                        return Str::slug(trim($matches[2]).' '.trim($matches[1]));
                    } elseif (preg_match('/^([^\s]+)(.*)$/', $name, $matches)) {
                        return Str::slug(trim($matches[2]).' '.trim($matches[1]));
                    }
                }
                return Str::slug($name);
            }
        ];
        
        return $fields;
    }
}
