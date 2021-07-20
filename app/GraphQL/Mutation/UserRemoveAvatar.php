<?php namespace Manivelle\GraphQL\Mutation;

use GraphQL;
use Request;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;

use Manivelle\User;

class UserRemoveAvatar extends Mutation
{
    protected $attributes = [
        'description' => 'Remove user\'s avatar'
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
            ]
        ];
    }
    
    public function resolve($root, $args)
    {
        $user = User::find($args['user_id']);
        foreach ($user->pictures as $picture) {
            $picture->delete();
        }
        $user->pictures()->sync([]);
        $user->load('pictures');
        
        return $user;
    }
}
