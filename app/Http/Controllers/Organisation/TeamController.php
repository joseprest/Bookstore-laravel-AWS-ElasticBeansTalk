<?php namespace Manivelle\Http\Controllers\Organisation;

use Panneau;

use Illuminate\Http\Request;

use Manivelle\Models\Organisation;

use Manivelle\Http\Controllers\Controller;

class TeamController extends Controller
{
    
    public function show(Request $request, Organisation $organisation, $userId)
    {
        $team = $organisation->getTeam();
        $user = $team->first(function ($key, $user) use ($userId) {
            return (isset($user->user_id) && (int)$user->user_id === (int)$userId) ||
                    (!isset($user->user_id) && (int)$user->id === (int)$userId);
        });
        
        if (!$user) {
            return abort(404);
        }
        
        return view('organisation.team.show', [
            'item' => $user
        ]);
    }
}
