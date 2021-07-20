<?php namespace Manivelle\Http\Controllers\Organisation;

use Panneau;
use Manivelle\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Manivelle\Models\Organisation;
use Manivelle\Models\Channel;
use Manivelle\Models\Screen;
use Event;
use Manivelle\Events\BubbleManuallyCreated;
use Localizer;

class BubblesController extends Controller
{

    public function create(Request $request, Organisation $organisation, $screen, $channelId)
    {
        $channel = Channel::findOrFail($channelId);

        $form = $this->form()
                        ->setRequest($request)
                        ->with([
                            'screen' => $screen,
                            'channel' => $channel
                        ]);

        return view('organisation.bubbles.form', array(
            'screen' => $screen,
            'channel' => $channel,
            'form' => $form
        ));
    }

    public function edit(Request $request, Organisation $organisation, $screen, $channelId, $id)
    {
        $channel = Channel::findOrFail($channelId);

        $item = $this->getItem($id);

        $form = $this->form()
                        ->setModel($item)
                        ->setRequest($request)
                        ->with([
                            'screen' => $screen,
                            'channel' => $channel
                        ]);

        return view('organisation.bubbles.form', array(
            'screen' => $screen,
            'channel' => $channel,
            'form' => $form,
            'model' => $item
        ));
    }

    public function store(Request $request, Organisation $organisation, $screen, $channelId)
    {
        $channel = Panneau::resource('channels')->find($channelId);

        //Form
        $form = $this->form()
                    ->setRequest($request)
                    ->with([
                        'screen' => $screen,
                        'channel' => $channel
                    ]);

        //Validation
        $rules = $form->getRules();
        if ($rules && sizeof($rules)) {
            $this->validate($request, $rules);
        }

        $data = $request->all();
        $data['organisation_id'] = !is_null($organisation) ? $organisation->id : null;
        $item = app('panneau')->resource('bubbles')
                                ->store($data);

        $channel->addBubble($item);

        Event::fire(new BubbleManuallyCreated($item));

        return redirect()->route(Localizer::routeName('organisation.screens.channel'), [$organisation->slug, $screen->id, $channelId]);
    }

    public function update(Request $request, Organisation $organisation, $screen, $channelId, $id)
    {
        $channel = Panneau::resource('channels')->find($channelId);

        $item = $this->getItem($id);

        //Form
        $form = $this->form()
                        ->setModel($item)
                        ->setRequest($request)
                        ->with([
                            'screen' => $screen,
                            'channel' => $channel
                        ]);

        //Validation
        $rules = $form->getRules();
        if ($rules && sizeof($rules)) {
            $this->validate($request, $rules);
        }

        $data = $request->all();
        app('panneau')->resource('bubbles')
            ->update($id, $data);

        return redirect()->route(Localizer::routeName('organisation.screens.channel'), [$organisation->slug, $screen->id, $channelId]);
    }

    protected function getItem($id)
    {
        try {
            return app('panneau')->resource('bubbles')->find($id);
        } catch (ResourceNotFoundException $e) {
            throw new NotFoundHttpException('Resource not found.');
        }
    }

    protected function form()
    {
        return app('panneau')->form('bubble');
    }
}
