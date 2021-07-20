<?php namespace Manivelle\Channels\Quizz;

use Manivelle\Support\BubbleType;

class QuestionBubble extends BubbleType
{
    protected $attributes = [
        'type' => 'quizz_question'
    ];

    public function attributes()
    {
        return [
            'label' => trans('bubbles/quizz_question.label')
        ];
    }

    public function fields()
    {
        return [
            [
                'name' => 'external_id',
                'type' => 'string',
                'label' => trans('bubbles/quizz_question.fields.external_id'),
                'hidden' => true,
            ],
            [
                'name' => 'question',
                'type' => 'string',
                'label' => trans('bubbles/quizz_question.fields.question')
            ],
            [
                'name' => 'image',
                'type' => 'picture',
                'label' => trans('bubbles/quizz_question.fields.image')
            ],
            [
                'name' => 'category',
                'type' => 'string',
                'label' => trans('bubbles/quizz_question.fields.category')
            ],
            [
                'name' => 'subcategory',
                'type' => 'string',
                'label' => trans('bubbles/quizz_question.fields.subcategory')
            ],
            [
                'name' => 'link',
                'type' => 'string',
                'label' => trans('bubbles/quizz_question.fields.link')
            ],
            [
                'name' => 'answers',
                'type' => 'quizz_answers',
                'label' => trans('bubbles/quizz_question.fields.answers')
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
                return $fields->category ? $fields->category:'';
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
                return $fields->link;
            }
        ]);
    }

    public function filters()
    {
        return [
            [
                'name' => 'question_category',
                'type' => 'tokens',
                'label' => trans('bubbles/quizz_question.filters.question_category'),
                'queryScope' => function ($query, $value) {
                    $query->whereHas('metadatas', function ($query) use ($value) {
                        $query->where('mediatheque_metadatables.metadatable_position', 'category');
                        $query->where('mediatheque_metadatas.value', 'LIKE', '%'.$value.'%');
                    });
                },
                'value' => function ($bubble, $fields) {
                    // return !empty($fields->category) ? $fields->category : 'general';
                    return $fields->category;
                },
                'tokens' => function ($params = []) {
                    return $this->getTokens('category', $params);
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
