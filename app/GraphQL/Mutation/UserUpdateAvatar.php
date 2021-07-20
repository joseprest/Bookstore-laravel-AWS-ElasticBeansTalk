<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\User;

class UserUpdateAvatar extends Mutation
{
    protected $attributes = [
        'description' => 'Update user\'s avatar'
    ];
    
    public function type()
    {
        return GraphQL::type('User');
    }
    
    public function args()
    {
        return [
            'user_id' => [
                'name' => 'user_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:users,id']
            ],
            'picture_id' => [
                'name' => 'picture_id',
                'type' => Type::string(),
                'rules' => ['required', 'exists:mediatheque_pictures,id']
            ]
        ];
    }
    
    public function resolve($root, $args)
    {
        $user = User::find($args['user_id']);
        $user->pictures()->sync([$args['picture_id']]);
        $user->load('pictures');
        
        return $user;
    }
}
