<?php namespace Manivelle\Http\Controllers;

use Auth;
use Panneau;
use Illuminate\Http\Request;
use Exception;
use Log;
use Artisan;
use Illuminate\Bus\Dispatcher;
use Manivelle\Support\SyncJob;

class WorkerController extends Controller
{
    public function queue(Request $request)
    {
        $data = $request->input('data');
        $job = unserialize($data['command']);
        try {
            if (!$job) {
                throw new Exception('No job found');
            }
                
            Log::info('[HTTP] Queue process: '. get_class($job));
            app(Dispatcher::class)->dispatchNow($job);
            if ($job instanceof SyncJob) {
                $job->setExists(false);
            }
        } catch (Exception $e) {
            Log::error($e);
            return abort(500);
        }
        
        return response('OK', 200);
    }
    
    public function scheduler(Request $request)
    {
        Artisan::call('schedule:run');
        Log::info('[HTTP] Schedule run: '.Artisan::output());
        
        return response('OK', 200);
    }
}
