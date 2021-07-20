<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;

class BubbleSeeder extends Seeder
{
    protected $items = [
        
    ];
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resource = Manivelle::resource('bubbles');
        
        //Sync screens
        foreach ($this->items as $data) {
            try {
                $model = $resource->find([
                    'handle' => $data['handle']
                ]);
            } catch (\Exception $e) {
                $model = null;
            }
            
            if (!$model) {
                $resource->store($data);
            } else {
                $resource->update($model->id, $data);
            }
            
            if (isset($data['channel'])) {
                $channel = Channel::where('handle', $data['channel'])->first();
                if ($channel) {
                    $channel->addBubble($model);
                }
            }
        }
    }
}
