<?php namespace Manivelle\Channels\Quizz\Fields;

use Panneau\Support\Field;
use Panneau\Fields\MetadataString;
use Panneau\Fields\MetadataBoolean;
use Panneau\Fields\Text;

class QuizzAnswerField extends Field
{
    protected $attributes = [
        'type' => 'quizz_answer'
    ];

    protected $fields = [
        'text' => \Panneau\Fields\MetadataString::class,
        'explanation' => \Panneau\Fields\Text::class,
        'good' => \Panneau\Fields\MetadataBoolean::class
    ];
}
