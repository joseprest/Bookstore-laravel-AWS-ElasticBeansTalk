<?php namespace Manivelle\Http\Controllers\Api;

use Illuminate\Http\Request;
use Manivelle\Http\Controllers\Controller;

use Manivelle\Models\Screen;
use Manivelle\Models\ScreenPing;
use Manivelle\Models\ScreenCommand;
use Exception;
use Log;
use Carbon\Carbon;

class ScreenController extends Controller
{
    public function ping(Request $request)
    {
        $input = $request->all();
        $screen = Screen::with('organisations', 'last_ping')
                        ->where('uuid', $input['uuid'])
                        ->first();

        if (!$screen) {
            return abort(404);
        }

        $screen->addPing($input);

        $commands = $screen->getCommandsToExecute();

        $now = Carbon::now();
        foreach ($commands as $command) {
            $command->sended_at = $now;
            $command->save();
        }

        return $commands;
    }

    public function command(Request $request, $id)
    {
        $input = $request->only(['return_code', 'output', 'executed_at']);

        $command = ScreenCommand::findOrFail($id);
        $command->fill($input);
        $command->save();

        return $command;
    }

    public function linked(Request $request)
    {
        $input = $request->all();
        $screen = Screen::with('organisations', 'last_ping')
                        ->where('uuid', $input['uuid'])
                        ->first();

        if (!$screen) {
            return abort(404);
        }

        return [
            'linked' => $screen->linked
        ];
    }

    public function authenticate(Request $request)
    {
        $input = $request->all();
        $screen = $this->getScreenFromInfos($input);
        if ($screen && isset($input['serial_number'])) {
            $fields = $screen->fields->toArray();
            $serialNumber = array_get($fields, 'technical.serial_number');
            if ($serialNumber !== $input['serial_number']) {
                array_set($fields, 'technical.serial_number', $serialNumber);
                $screen->saveFields($fields);
                $screen = $this->getScreenFromInfos($input);
            }
        }

        return $screen;
    }

    public function create(Request $request)
    {
        $input = $request->all();
        $uuid = array_get($input, 'uuid', '');
        $serial_number = array_get($input, 'serial_number');
        $screen = null;
        $fields = [];
        if (!empty($serial_number)) {
            array_set($fields, 'technical.serial_number', $serial_number);
        }

        if (!empty($serial_number)) {
            $screen = $this->getScreenFromInfos([
                'serial_number' => $serial_number
            ]);
        } else {
            $screen = $this->getScreenFromInfos([
                'uuid' => $uuid
            ]);
        }
        
        if ($screen) {
            if (!empty($serial_number)) {
                $fields = $screen->fields->toArray();
                array_set($fields, 'technical.serial_number', $serial_number);
                $screen->saveFields($fields);
                $screen = Screen::with('organisations', 'last_ping', 'metadatas', 'texts')
                                ->where('id', $screen->id)
                                ->first();
            }
            return $screen;
        }

        $screen = new Screen();
        $screen->fill([
            'uuid' => $uuid
        ]);
        $screen->save();
        $screen->saveFields($fields);

        return $screen;
    }

    protected function getScreenFromInfos($info)
    {
        if (isset($info['uuid'])) {
            return Screen::with('organisations', 'last_ping', 'metadatas', 'texts')
                            ->where('uuid', $info['uuid'])
                            ->first();
        } elseif (isset($info['serial_number'])) {
            return Screen::with('organisations', 'last_ping', 'metadatas', 'texts')
                            ->whereHas('metadatas', function ($query) use ($info) {
                                $query->where('mediatheque_metadatas.name', 'technical[serial_number]');
                                $query->where('mediatheque_metadatas.value', $info['serial_number']);
                            })
                            ->orderBy('id', 'asc')
                            ->first();
        }

        return null;
    }
}
