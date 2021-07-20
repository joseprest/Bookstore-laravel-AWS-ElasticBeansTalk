<?php namespace Manivelle\Banq\Quizz;

use Manivelle\Support\ChannelType;

use Manivelle\Panneau\Fields\AuthorField;
use Manivelle\Panneau\Fields\StringField;
use Manivelle\Models\Bubble;
use Panneau\Fields\Date as DateField;

class QuizzChannel extends ChannelType
{

    protected $attributes = [
        'type' => 'banq_quizz',
        'bubbleType' => 'banq_question'
    ];

    public function settings()
    {
        return [];
    }
    
    public function filters()
    {
        
        return [
            [
                'name' => 'question_category',
                'label' => trans('channels/banq_quizz.filters.question_category'),
                'field' => 'select',
                'type' => 'question',
                'values' => function () {
                    return $this->getTokens('subcategory');
                }
            ]
            
        ];
    }
}
