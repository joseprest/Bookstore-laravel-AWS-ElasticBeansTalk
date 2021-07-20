<?php namespace Manivelle\Http\Controllers\Admin;

use Manivelle\Http\Controllers\Controller;
use Manivelle\Models\Source;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Localizer;
use Panneau;
use Manivelle\Support\Str;

class ImportationsController extends Controller
{
    public function index()
    {
        $sources = Source::all();

        $sourcesData = $sources->map(function ($source) {
            $isSyncing = $source->isSyncing();

            return [
                'name' => $source->name,
                'url' => route(
                    Localizer::routeName('admin.importations.source'),
                    ['source' => $source->id ]
                ),
                'isSyncing' => $isSyncing,
            ];
        });

        return view('admin.importations.index', [
            'sources' => $sourcesData,
        ]);
    }

    public function source(Source $source)
    {
        $durationFormat = trans('importation.last.durationFormat');
        $lastSyncs = self::getLastSyncs($source);
        $lastSyncsData = $lastSyncs->map(function ($sync) use ($durationFormat) {
            $isRunning = $sync->isStarted();
            $isFinished = $sync->isFinished();
            $isStopped = !$isRunning && !$isFinished;

            $status = $isFinished ? 'finished' : ($isRunning ? 'started' : 'stopped');
            $startedAt = Carbon::parse($sync->started_at);
            $finishedAt = Carbon::parse($sync->finished_at);
            $durationUntil = $isFinished ? $finishedAt : Carbon::now();
            $duration = $isStopped ? '---' : $durationUntil->diff($startedAt)->format($durationFormat);

            return [
                'status' => $status,
                'statusString' => trans('importation.last.' . $status),
                'startedAt' => Str::formatDate($startedAt),
                'finishedAt' => $isFinished ? Str::formatDate($finishedAt) : trans('importation.last.not_finished'),
                'duration' => $duration
            ];
        });

        $hasLibraryList = self::sourceHasLibraryList($source);
        $libraries = null;

        if ($hasLibraryList) {
            $selectedLibraries = array_map(function ($library) {
                $count = Panneau::resource('bubbles')->query([
                    'filter_book_library' => $library['key']
                ])->count();

                return array_merge($library, [
                    'count' => $count
                ]);
            }, self::getSelectedLibraries($source));

            $libraries = [
                'selectedLibraries' => $selectedLibraries,
                'editLink' => Localizer::route(
                    'admin.importations.source.editLibraries',
                    ['source' => $source->id]
                )
            ];
        }

        return view('admin.importations.source', [
            'source' => $source,
            'lastSyncs' => $lastSyncsData,
            'hasLibraryList' => $hasLibraryList,
            'libraries' => $libraries
        ]);
    }

    public function editLibraries(Source $source)
    {
        if (!self::sourceHasLibraryList($source)) {
            return redirect()->route(
                Localizer::routeName('admin.importations.source'),
                ['source' => $source->id]
            );
        }

        $allLibraries = self::getAllLibraries();

        return view('admin.importations.editLibraries', [
            'source' => $source,
            'allLibraries' => $allLibraries,
            'selectedLibraries' => self::getSelectedLibraries($source),
            'sourceURL' => route(
                Localizer::routeName('admin.importations.source'),
                ['source' => $source->id]
            ),
            'savePOSTURL' => route(
                Localizer::routeName('admin.importations.source.saveLibraries'),
                ['source' => $source->id]
            )
        ]);
    }

    public function saveLibraries(Request $request, Source $source)
    {
        $newLibraries = [];

        if ($request->has('libraries')) {
            $newLibraries = array_keys($request->input('libraries'));
        }

        $updatedSettings = is_array($source->settings) ? $source->settings : [];
        $updatedSettings['libraries'] = $newLibraries;
        $source->settings = $updatedSettings;
        $source->save();

        return redirect()->route(
            Localizer::routeName('admin.importations.source'),
            ['source' => $source->id]
        );
    }

    protected static function getSelectedLibraries($source)
    {
        $sourceType = $source->getSourceType();
        $libraryKeys = $sourceType->getLibraries();

        $allLibraries = self::getAllLibraries();
        $libraries = [];

        foreach ($allLibraries as $library) {
            if (in_array($library['key'], $libraryKeys)) {
                $libraries[$library['key']] = $library;
            }
        }

        return $libraries;
    }

    protected static function getLastSyncs($source)
    {
        return $source->syncs()
            ->take(10)
            ->orderBy('started_at', 'desc')
            ->get();
    }

    protected static function sourceHasLibraryList($source)
    {
        $sourcesWithLibraryList = config('manivelle.core.sourcesWithLibraryList');

        return in_array($source->handle, $sourcesWithLibraryList);
    }

    protected static function getAllLibraries()
    {
        return config('manivelle.channels.books.libraries');
    }
}
