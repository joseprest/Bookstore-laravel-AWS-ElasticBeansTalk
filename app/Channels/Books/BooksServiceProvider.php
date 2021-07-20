<?php namespace Manivelle\Channels\Books;

use GraphQL;
use Manivelle\Support\ChannelServiceProvider;

class BooksServiceProvider extends ChannelServiceProvider
{
    protected $channelTypes = [
        \Manivelle\Channels\Books\BooksChannel::class
    ];
    
    protected $bubbleTypes = [
        \Manivelle\Channels\Books\BookBubble::class
    ];
    
    protected $fields = [
        \Manivelle\Channels\Books\Fields\BookCategory::class,
        \Manivelle\Channels\Books\Fields\BookLibrary::class,
        \Manivelle\Channels\Books\Fields\BookLibraries::class,
        \Manivelle\Channels\Books\Fields\PretNumeriqueId::class,
        \Manivelle\Channels\Books\Fields\PretNumeriqueIds::class
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
            $this->app['graphql']->addType(\Manivelle\Channels\Books\GraphQL\BubbleBookCategoryFieldType::class, 'BubbleBookCategoryField');
        }
    }
}
