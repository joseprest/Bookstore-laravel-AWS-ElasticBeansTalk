<?php namespace Manivelle\Http\Controllers\Screen;

use Panneau;
use Manivelle;

use Manivelle\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Manivelle\Models\Screen;
use Manivelle\Models\Bubble;
use Manivelle\Support\Str;

class DataController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        \Debugbar::disable();
    }

    public function version(Request $request, Screen $screen)
    {
        $lastUpdate = str_replace(':', '_', $screen->getLastUpdate()->toDateTimeString());
        $interfaceVersion = str_replace('.', '_', 'v'.trim(file_get_contents(public_path('vendor/manivelle-interface/version'))));
        $package = json_decode(file_get_contents(base_path('package.json')), true);
        $backendVersion = 'v'.str_replace('.', '_', array_get($package, 'version', '0.0.0'));
        return [
            'data' => Str::slug($lastUpdate),
            'interface' => Str::slug($interfaceVersion.'_'.$lastUpdate),
            'backend' => Str::slug($backendVersion)
        ];
    }

    public function screen(Request $request, Screen $screen)
    {
        return $screen;
    }

    public function bubbles(Request $request, Screen $screen)
    {
        $cache = Manivelle::cache($screen, 'bubbles_ids');

        return $this->cacheResponse($request, $cache);
    }

    public function bubble(Request $request, Screen $screen, Bubble $bubble)
    {
        $cache = Manivelle::cache($bubble, 'json');

        return $this->cacheResponse($request, $cache);
    }

    public function bubble_page(Request $request, Screen $screen, $count = 100, $page = null)
    {
        if ($page === null) {
            $page = $count;
            $count = 100;
        }
        $cache = Manivelle::cache(Bubble::class, 'page_json')
            ->setItem([
                'page' => $page,
                'count' => $count
            ]);

        return $this->cacheResponse($request, $cache);
    }

    public function channels(Request $request, Screen $screen)
    {
        $cache = Manivelle::cache($screen, 'channels');

        return $this->cacheResponse($request, $cache);
    }

    public function channels_filters(Request $request, Screen $screen)
    {
        $cache = Manivelle::cache($screen, 'channels');

        return $this->cacheResponse($request, $cache);
    }

    public function timeline(Request $request, Screen $screen)
    {
        $cache = Manivelle::cache($screen, 'timeline');

        return $this->cacheResponse($request, $cache);
    }

    protected function cacheResponse(Request $request, $cache)
    {
        if ($request->has('forget')) {
            $cache->forget();
        }

        $content = $request->has('no_cache') ? $cache->getData():$cache->get();

        return with(new Response($content, 200))
                        ->header('Content-Type', 'application/json');
    }
}
