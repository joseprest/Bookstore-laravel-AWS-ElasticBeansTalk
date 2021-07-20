<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Manivelle\Models\Organisation;
use Manivelle\User;
use Manivelle\Models\Channel;
use Manivelle\Models\Role;

class OrganisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organisations = include __DIR__.'/data/organisations.php';
        
        //Sync organisations
        foreach ($organisations as $organisationData) {
            if (!$organisation = Organisation::where('slug', $organisationData['slug'])->first()) {
                $organisation = new Organisation();
            }
            
            $organisation->fill($organisationData);
            $organisation->save();
            
            foreach ($organisationData['users'] as $organisationUser) {
                $user = User::where('email', $organisationUser['email'])->first();
                $role = Role::where('slug', $organisationUser['role'])->first();
                if ($user && $role) {
                    $organisation->attachUser($user, $role);
                }
            }
        }
    }
}
