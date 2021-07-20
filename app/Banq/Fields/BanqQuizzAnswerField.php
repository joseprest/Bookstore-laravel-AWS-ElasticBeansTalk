<?php namespace Manivelle\Banq\Fields;

use Panneau\Support\Field;
use Panneau\Fields\MetadataString;
use Panneau\Fields\MetadataBoolean;
use Panneau\Fields\Text;

class BanqQuizzAnswerField extends Field
{
    protected $attributes = [
        'type' => 'banq_quizz_answer'
    ];
    
    protected $fields = [
        'text' => \Panneau\Fields\MetadataString::class,
        'explanation' => \Panneau\Fields\Text::class,
        'good' => \Panneau\Fields\MetadataBoolean::class
    ];
}
