<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Manivelle\Models\Screen;
use Manivelle\Models\Organisation;
use Manivelle\Models\Channel;
use Manivelle\Models\Bubble;

class ScreenSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        //Sync screens
        $items = include __DIR__.'/data/screens.php';
        foreach ($items as $data) {
            $query = Screen::query();
            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            } elseif (isset($data['auth_code'])) {
                $query->where('auth_code', $data['auth_code']);
            }
            
            $model = $query->first();
            
            if (!$model && isset($data['id'])) {
                continue;
            } elseif (!$model) {
                $model = new Screen();
            }
            
            if (isset($data['auth_code'])) {
                $model->auth_code = $data['auth_code'];
            }
            $model->fill($data);
            $model->save();
            
            if (isset($data['fields'])) {
                $model->saveFields(array_get($data, 'fields', []));
            }
            
            $organisation = null;
            if (isset($data['organisations'])) {
                foreach ($data['organisations'] as $organisationSlug) {
                    $organisation = Organisation::where('slug', $organisationSlug)->first();
                    $organisation->linkScreen($model);
                }
            }
            if (!$organisation) {
                $organisation = sizeof($model->organisations) ? $model->organisations[0]:null;
            }
            
            if (isset($data['channels'])) {
                foreach ($data['channels'] as $channelHandle) {
                    $channel = Channel::where('handle', $channelHandle)->first();
                    $model->attachChannel($channel, $organisation);
                }
            }
            
            if (isset($data['bubbles'])) {
                foreach ($data['bubbles'] as $organisationSlug => $bubbleHandles) {
                    $organisation = Organisation::where('slug', $organisationSlug)->first();
                    $playlist = $model->playlists()
                                        ->where('playlists.type', 'organisation.screen.slideshow')
                                        ->where('screens_playlists_pivot.organisation_id', $organisation->id)
                                        ->first();
                    $bubbles = array();
                    foreach ($bubbleHandles as $bubbleHandle) {
                        $bubble = Bubble::where('handle', $bubbleHandle)->first();
                        $bubbles[] = $bubble->id;
                    }
                    $playlist->bubbles()->sync($bubbles);
                }
            }
        }
    }
}
