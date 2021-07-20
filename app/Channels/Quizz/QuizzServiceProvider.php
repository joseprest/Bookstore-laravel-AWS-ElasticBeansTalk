<?php namespace Manivelle\Channels\Quizz;

use Manivelle\Support\ChannelServiceProvider;

class QuizzServiceProvider extends ChannelServiceProvider
{
    protected $channelTypes = [
        \Manivelle\Channels\Quizz\QuizzChannel::class
    ];

    protected $bubbleTypes = [
        \Manivelle\Channels\Quizz\QuestionBubble::class
    ];

    protected $fields = [
        'quizz_answer' => \Manivelle\Channels\Quizz\Fields\QuizzAnswerField::class,
        'quizz_answers' => \Manivelle\Channels\Quizz\Fields\QuizzAnswersField::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        if ($this->app->bound('graphql')) {
            app('graphql')->addType(\Manivelle\Channels\Quizz\GraphQL\BubbleQuizzAnswersFieldType::class, 'BubbleQuizzAnswersField');
            app('graphql')->addType(\Manivelle\Channels\Quizz\GraphQL\QuizzAnswerType::class, 'QuizzAnswer');
        }
    }
}
