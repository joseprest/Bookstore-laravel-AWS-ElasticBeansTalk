<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Manivelle\User;
use Manivelle\Models\Role;

class UserSeeder extends Seeder
{

    protected $roles = [
        [
            'name' => 'admin',
            'slug' => 'admin',
            'level' => 1
        ],
        [
            'name' => 'organisation_admin',
            'slug' => 'organisation.admin',
            'level' => 3
        ],
        [
            'name' => 'organisation_editor',
            'slug' => 'organisation.editor',
            'level' => 4
        ]
    ];

    protected $users = [
        [
            'email' => 'info@atelierfolklore.ca',
            'password' => 'papouasie',
            'roles' => array('admin')
        ],
        [
            'email' => 'tech@manivelle.io',
            'password' => 'm4n1v3ll3',
            'roles' => array('admin')
        ],
        [
            'email' => 'info@banq.qc.ca',
            'password' => 'm4n1v3ll3',
            'roles' => array()
        ],
        [
            'email' => 'bibliotheque@ville.brossard.ca',
            'password' => 'm4n1v3ll3',
            'roles' => array()
        ],
        [
            'email' => 'info@univ-brest.fr',
            'password' => 'Hfwe98y832rlhn',
            'roles' => array()
        ]
    ];
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Sync roles
        foreach ($this->roles as $roleData) {
            if (!$role = Role::where('slug', $roleData['slug'])->first()) {
                $role = new Role();
            }
            $role->fill($roleData);
            $role->save();
        }
        
        //Sync users
        foreach ($this->users as $userData) {
            if (!$user = User::where('email', $userData['email'])->first()) {
                $user = new User();
            }
            
            $user->email = $userData['email'];
            $user->password = bcrypt($userData['password']);
            $user->save();
            
            if (isset($userData['roles'])) {
                $user->detachAllRoles();
                foreach ($userData['roles'] as $roleSlug) {
                    $role = Role::where('slug', $roleSlug)->first();
                    if ($role) {
                        $user->attachRole($role);
                    }
                }
            }
        }
    }
}
