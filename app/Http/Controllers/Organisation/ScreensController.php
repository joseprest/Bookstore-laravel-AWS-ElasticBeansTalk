<?php namespace Manivelle\Http\Controllers\Organisation;

use Panneau;

use Manivelle\Models\Organisation;
use Manivelle\Models\OrganisationScreen;
use Manivelle\Models\Screen;
use Manivelle\Models\Channel;

use Illuminate\Http\Request;
use Manivelle\Http\Requests\ScreenLinkRequest;
use Localizer;

class ScreensController extends ResourceController
{
    protected $resource = 'screens';
    
    protected $itemsLists = array(
        'index' => 'organisation.screens'
    );
    protected $views = array(
        'index' => 'panneau::list',
        'show' => 'organisation.screens.show',
        'create' => 'panneau::form',
        'edit' => 'panneau::form'
    );
    
    protected $forms = array(
        'create' => 'screen.settings',
        'edit' => 'screen.settings'
    );
    
    /**
     * Show
     */
    public function show(Request $request, Organisation $organisation, $item)
    {
        return $this->viewShow(array(
            'item' => $item
        ));
    }
    
    /**
     * Channel
     */
    public function channel(Request $request, Organisation $organisation, $item, $channelId)
    {
        $channel = $item->channels->first(function ($key, $value) use ($channelId) {
            return (int)$value->channel_id === (int)$channelId;
        });
        
        $channel->withoutFiltersValues();
        
        return view('organisation.screens.channel', [
            'item' => $item,
            'channel' => $channel
        ]);
    }
    
    /**
     * Link screen to organisation
     */
    public function link(ScreenLinkRequest $request, Organisation $organisation)
    {
        $screen = Screen::where('auth_code', $request->get('auth_code'))->first();
        $organisation->linkScreen($screen);
        
        $screen = $organisation->screens()->where('id', $screen->id)->first();
        
        if ($request->wantsJson()) {
            return $screen;
        } else {
            return redirect()->route(Localizer::routeName('organisation.home'), [$organisation->slug]);
        }
    }
    
    /**
     * Unlink screen to organisation
     */
    public function unlink(Request $request, Organisation $organisation, $screen)
    {
        $screen = $screen->screen;
        $organisation->unlinkScreen($screen);
        
        if ($request->wantsJson()) {
            return $screen;
        } else {
            return redirect()->route(
                Localizer::routeName('organisation.home'),
                [$organisation->slug]
            );
        }
    }
    
    /**
     * Update channel settings
     */
    public function channel_settings_update(Request $request, Organisation $organisation, $id, $channelId = null)
    {
        $screen = is_object($id) && isset($id->screen) ? $id->screen:$this->getItem($id);
        
        $screenChannel = null;
        if ($channelId) {
            $screenChannel = $screen->channels->first(function ($key, $value) use ($channelId) {
                return (int)$value->channel_id === (int)$channelId;
            });
        }
        
        $channelType = $screenChannel->getChannelType();
        $settings = $channelType->getSettings();
        $settingsField = array_pluck($settings, 'name');
        
        $input = $request->input('screen_settings');
        $screenChannel->settings = json_decode($input);
        $screenChannel->save();
        
        return redirect()->route(
            Localizer::routeName('organisation.screens.channel'),
            [$organisation->slug, $screen->id, $screenChannel->channel_id]
        );
    }
    
    /**
     * Update
     */
    public function update(Request $request, Organisation $organisation, $screen)
    {
        $model = $this->getItem($screen->screen_id);
        
        //Form
        $form = $this->formEdit()
                        ->setModel($model)
                        ->setRequest($request);
        
        //Validation
        $rules = $form->getRules();
        if ($rules && sizeof($rules)) {
            $this->validate($request, $rules);
        }
        
        $data = $request->all();
        $this->resource->update($screen->screen_id, $data);

        if ($request->wantsJson()) {
            return $model;
        } else {
            return redirect()->route(
                Localizer::routeName('organisation.screens.settings'),
                [$organisation->slug, $model->id]
            );
        }
    }
}
