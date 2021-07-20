<?php namespace Manivelle\Http\Controllers;

use Auth;
use Panneau;
use Illuminate\Http\Request;
use Debugbar;

class GraphQLController extends Controller
{
    public function query(Request $request)
    {
        $debug = $request->has('DEBUG');
        
        if ($debug) {
            Debugbar::enable();
        }
        
        $query = $request->get('query');
        $params = $request->get('params');
        
        $response = app('graphql')->query($query, is_string($params) ? json_decode($params, true):$params);
        
        if ($debug) {
            return view('graphql.debug', [
                'response' => $response
            ]);
        }
        
        return $response;
    }
}
