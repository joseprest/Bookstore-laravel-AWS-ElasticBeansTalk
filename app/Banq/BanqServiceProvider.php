<?php namespace Manivelle\Banq;

use GraphQL;
use Manivelle\Support\ChannelServiceProvider;

class BanqServiceProvider extends ChannelServiceProvider
{
    protected $fields = [
        'banq_quizz_answers' => \Manivelle\Banq\Fields\BanqQuizzAnswersField::class,
        'banq_quizz_answer' => \Manivelle\Banq\Fields\BanqQuizzAnswerField::class
    ];
    
    protected $channelTypes = [
        \Manivelle\Banq\Quizz\QuizzChannel::class,
        \Manivelle\Banq\Books\BooksChannel::class,
        \Manivelle\Banq\Cards\CardsChannel::class,
        \Manivelle\Banq\Photos\PhotosChannel::class,
        \Manivelle\Banq\Services\ServicesChannel::class
    ];
    
    protected $bubbleTypes = [
        \Manivelle\Banq\Quizz\QuestionBubble::class,
        \Manivelle\Banq\Books\BookBubble::class,
        \Manivelle\Banq\Photos\PhotoBubble::class,
        \Manivelle\Banq\Cards\CardBubble::class,
        \Manivelle\Banq\Services\ServiceBubble::class
    ];
    
    protected $sourceTypes = [
        'banq_books' => \Manivelle\Banq\Sources\BanqBooks::class,
        'banq_cards' => \Manivelle\Banq\Sources\BanqCards::class,
        'banq_photos' => \Manivelle\Banq\Sources\BanqPhotos::class,
        'banq_quizz' => \Manivelle\Banq\Sources\BanqQuizz::class
    ];
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        
        /*$configPath = __DIR__.'/../config';
        $translationsPath = __DIR__.'/../resources/lang';
        
        $this->loadTranslationsFrom($translationsPath, 'manivelle-banq');
        $this->mergeConfigFrom($configPath.'/banq.php', 'manivelle.banq.core');
        $this->mergeConfigFrom($configPath.'/books.php', 'manivelle.banq.books');
        $this->mergeConfigFrom($configPath.'/cards.php', 'manivelle.banq.cards');
        $this->mergeConfigFrom($configPath.'/photos.php', 'manivelle.banq.photos');
        
        $this->publishes([
            $configPath => config_path('manivelle/banq/'),
        ], 'config');
        
        $this->publishes([
            $translationsPath => base_path('resources/lang/vendor/manivelle-banq'),
        ], 'translations');*/
        
        if ($this->app->bound('graphql')) {
            app('graphql')->addType(\Manivelle\Banq\GraphQL\Type\BubbleBanqQuizzAnswersFieldType::class, 'BubbleBanqQuizzAnswersField');
            app('graphql')->addType(\Manivelle\Banq\GraphQL\Type\BanqQuizzAnswerType::class, 'BanqQuizzAnswer');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }
}
