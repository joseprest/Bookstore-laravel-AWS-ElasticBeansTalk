<?php namespace Manivelle\Banq\Fields;

use Panneau\Support\Field;
use Panneau\Fields\MetadataString;
use Panneau\Fields\MetadataBoolean;
use Panneau\Fields\Text;

class BanqQuizzAnswersField extends Field
{
    protected $attributes = [
        'type' => 'banq_quizz_answers'
    ];
    
    protected $hasMany = \Manivelle\Banq\Fields\BanqQuizzAnswerField::class;
}
