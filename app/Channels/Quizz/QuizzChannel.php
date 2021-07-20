<?php namespace Manivelle\Channels\Quizz;

use Manivelle\Support\ChannelType;

use Manivelle\Models\Bubble;

class QuizzChannel extends ChannelType
{

    protected $attributes = [
        'type' => 'quizz',
        'bubbleType' => 'quizz_question'
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
                'label' => trans('channels/quizz.filters.question_category'),
                'field' => 'select',
                'type' => 'question',
                'values' => function () {
                    $tokens = $this->getTokens('category');
                    // $tokens[] = [
                    //     'value' => 'general',
                    //     'label' => 'Général',
                    // ];
                    return $tokens;
                }
            ]

        ];
    }
}
