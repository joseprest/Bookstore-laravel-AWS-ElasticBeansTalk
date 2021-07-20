<?php namespace Manivelle\Http\Controllers\Screen;

use Panneau;
use Cache;
use Exception;
use Log;
use Mail;
use Config;
use Illuminate\Http\Request;

use Manivelle\Http\Controllers\Controller;
use Manivelle\Http\Requests\ShareEmailRequest;
use Manivelle\Http\Requests\ShareSMSRequest;
use Manivelle\Http\Requests\ShareMessageRequest;

use Manivelle\Models\Screen;
use Manivelle\Models\Bubble;

use Manivelle\Jobs\ShareBubbleEmailJob;
use Manivelle\Jobs\ShareBubbleSMSJob;

class ApiController extends Controller
{

    public function screen_update(Request $request, Screen $screen)
    {
        $input = $request->all();

        $fields = $screen->getFields()->toArray();

        if ($request->has('position')) {
            array_set($fields, 'location.position', $request->input('position'));
        }

        if ($request->has('resolution')) {
            array_set($fields, 'technical.resolution', $request->input('resolution'));
        }

        $screen->saveFields($fields);

        return Screen::find($screen->id);
    }

    public function share_email(ShareEmailRequest $request, Screen $screen)
    {
        $bubble = Bubble::findOrFail($request->input('bubble_id'));
        $locale = config('app.locale');
        $organisation = !$screen->organisations->isEmpty() ? $screen->organisations[0]:null;
        $from = $organisation ? $organisation->email_from:config('mail.from');
        $subject = $organisation ? $organisation->email_subject:trans('share.email.subject');
        $to = $request->input('email');
        $message = $request->has('message') ? [
            'from' => $request->input('from'),
            'body' => $request->input('message')
        ]:null;

        $email = [
            'to' => $to,
            'from' => $from,
            'message' => $message,
            'subject' => $subject
        ];

        $this->dispatch(new ShareBubbleEmailJob($bubble, $email, $organisation, $locale));

        return [
            'success' => true
        ];
    }

    public function share_sms(ShareSMSRequest $request, Screen $screen)
    {
        $phone = $request->input('phone');
        $bubble = Bubble::findOrFail($request->input('bubble_id'));
        $organisation = !$screen->organisations->isEmpty() ? $screen->organisations[0]:null;
        $locale = config('app.locale');
        $countryCode = array_get($screen->settings, 'countryCode', '+1');

        $this->dispatch(new ShareBubbleSMSJob($bubble, $phone, $countryCode, $organisation, $locale));

        return [
            'success' => true
        ];
    }

    public function share_message(ShareMessageRequest $request, Screen $screen)
    {
        $bubble = Bubble::findOrFail($request->input('bubble_id'));
        $locale = config('app.locale');
        $organisation = !$screen->organisations->isEmpty() ? $screen->organisations[0]:null;
        $to = $request->input('email');
        $from = $organisation ? $organisation->email_from:config('mail.from');
        $subject = $organisation ? $organisation->email_subject:trans('share.email.subject');
        $message = $request->has('message') ? [
            'from' => $request->input('from'),
            'body' => $request->input('message')
        ]:null;

        $email = [
            'to' => $to,
            'from' => $from,
            'message' => $message,
            'subject' => $subject
        ];

        $this->dispatch(new ShareBubbleEmailJob($bubble, $email, $organisation, $locale));

        return [
            'success' => true
        ];
    }

    public function test_message(Request $request, Screen $screen)
    {
        $email = $request->input('email');
        $bubble = Bubble::findOrFail($request->input('bubble_id'));
        $locale = config('app.locale');
        $organisation = !$screen->organisations->isEmpty() ? $screen->organisations[0]:null;
        $from = $organisation ? $organisation->email_from:config('mail.from');
        $message = [
            'from' => $request->input('from'),
            'body' => $request->input('message')
        ];

        return view('emails.share_message', [
            'email' => $email,
            'bubble' => $bubble,
            'organisation' => $organisation,
            'message' => $message,
            'locale' => $locale,
            'url' => $bubble->snippet->link
        ]);
    }
}
