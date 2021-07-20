<?php namespace Manivelle\Channels\Quizz\Fields;

use Panneau\Support\Field;
use Panneau\Fields\MetadataString;
use Panneau\Fields\MetadataBoolean;
use Panneau\Fields\Text;

class QuizzAnswersField extends Field
{
    protected $attributes = [
        'type' => 'quizz_answers'
    ];

    protected $hasMany = \Manivelle\Channels\Quizz\Fields\QuizzAnswerField::class;
}
