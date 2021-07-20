<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '512M');
        
        Model::reguard();
        
        $eventDisptacher = Model::getEventDispatcher();
        Model::unsetEventDispatcher();
        
        $this->call(UserSeeder::class);
        $this->call(SourcesSeeder::class);
        $this->call(OrganisationSeeder::class);
        $this->call(ChannelSeeder::class);
        $this->call(ScreenSeeder::class);
        
        Model::setEventDispatcher($eventDisptacher);
    }
}
