<?php namespace Manivelle\Banq\Quizz;

use Manivelle\Support\BubbleType;

class QuestionBubble extends BubbleType
{
    protected $attributes = [
        'type' => 'banq_question'
    ];

    public function attributes()
    {
        return [
            'label' => trans('bubbles/banq_question.label')
        ];
    }

    public function fields()
    {
        return [
            [
                'name' => 'banq_id',
                'type' => 'string',
                'label' => trans('bubbles/banq_question.fields.banq_id')
            ],
            [
                'name' => 'question',
                'type' => 'string',
                'label' => trans('bubbles/banq_question.fields.question')
            ],
            [
                'name' => 'image',
                'type' => 'picture',
                'label' => trans('bubbles/banq_question.fields.image')
            ],
            [
                'name' => 'category',
                'type' => 'string',
                'label' => trans('bubbles/banq_question.fields.category')
            ],
            [
                'name' => 'subcategory',
                'type' => 'string',
                'label' => trans('bubbles/banq_question.fields.subcategory')
            ],
            [
                'name' => 'answers',
                'type' => 'quizz_answers',
                'label' => trans('bubbles/banq_question.fields.answers')
            ]
        ];
    }

    public function snippet()
    {
        $snippet = parent::snippet();

        return array_merge($snippet, [
            'title' => function ($fields) {
                return $fields->question ? $fields->question:'';
            },
            'subtitle' => function ($fields) {
                return $fields->subcategory ? $fields->subcategory:'';
            },
            'picture' => function ($fields) {
                return $fields->image ? $fields->image:null;
            },
            'description' => function ($fields) {
                $description = [];
                return implode("\n", $description);
            },
            'summary' => function ($fields) {
                return '';
            },
            'link' => function ($fields) {
                return 'http://www.banq.qc.ca/services/saviez_vous/reponse.html?id='.$fields->banq_id;
            }
        ]);
    }

    public function filters()
    {
        return [
            [
                'name' => 'question_category',
                'type' => 'tokens',
                'label' => trans('bubbles/banq_question.filters.question_category'),
                'queryScope' => function ($query, $value) {
                    $query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatables.metadatable_position', 'subcategory');
                        $query->where('mediatheque_metadatas.value', 'LIKE', '%'.$value.'%');
                    });
                },
                'value' => function ($bubble, $fields) {
                    return $fields->subcategory;
                },
                'tokens' => function ($params = []) {
                    return $this->getTokens('subcategory', $params);
                }
            ]
        ];
    }

    /**
     * Email
     */
    public function getEmailLayout()
    {
        return 'photo-small';
    }

    public function getEmailTopButton($url)
    {
        return [
            'label' => trans('share.bubbles.banq_question.see_entry'),
            'url' => $url
        ];
    }
}
