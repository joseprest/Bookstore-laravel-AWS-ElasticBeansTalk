<?php namespace Manivelle\View\Composers\Emails;

use View;

use Illuminate\Http\Request;
use Pelago\Emogrifier;
use Manivelle\Support\Str;

class ShareMessageComposer
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function compose($view)
    {
        $bubble = $view->bubble;

        $bubble->loadIfNotLoaded([
            'channels',
            'channels.metadatas',
            'channels.texts',
            'channels.pictures',
        ]);
        $data = $bubble->toArray();
        $channels = $bubble->channels->toArray();

        $bubbleType = $bubble->bubbleType();
        $view->data = $data;
        $view->title = array_get($data, 'snippet.title', null);
        $view->subtitle = array_get($data, 'snippet.subtitle', null);
        $view->typeName = array_get($channels, '0.snippet.title', $bubbleType->label);
        $view->layout = $bubbleType->getEmailLayout();
        $view->topButton = $bubbleType->getEmailTopButton($view->url);
        $view->bottomButton = $bubbleType->getEmailBottomButton($view->url);
        $view->fields = $this->getFields($bubble, $data, $bubbleType->getEmailFields());
        $view->texts = $bubbleType->getEmailTexts();

        $view->logo = asset('img/emails/logo.png');
        $view->logoWidth = 32;
        $view->logoHeight = 18;
        if (isset($view->organisation) && $view->organisation->slug === 'banq') {
            $view->logo = asset('img/emails/banq/logo.png');
            $view->logoWidth = 67;
            $view->logoHeight = 18;
            $view->facebookLink = 'https://www.facebook.com/banqweb20';
            $view->twitterLink = 'https://twitter.com/_banq';
        } elseif (isset($view->organisation) && $view->organisation->slug === 'vaudreuil') {
            $view->logo = asset('img/emails/vaudreuil/logo-vaudreuil.png');
            $view->logoWidth = 113;
            $view->logoHeight = 60;
            // $view->facebookLink = 'https://www.facebook.com/JeSuisVD/';
            $view->texts['footer'] = trans('share.email.footer_vaudreuil');
            if (isset($view->bottomButton)) {
                $type = array_get($channels, '0.type', null);
                if ($type === 'events') {
                    $view->bottomButton['url'] = 'https://jesuismozaik.com/agenda/';
                } elseif ($type === 'announcements') {
                    $view->bottomButton['url'] = 'https://jesuismozaik.com/journal/';
                } elseif ($type === 'locations') {
                    $view->bottomButton['url'] = 'https://jesuismozaik.com/lieux/';
                }
            }
        }

        $emogrifier = new Emogrifier();
        $html = view('emails.partials.message', $view->getData())->render();
        $css = file_get_contents(public_path('css/emails/banq.css'));
        if (isset($view->organisation) && $view->organisation->slug === 'vaudreuil') {
            $css .= file_get_contents(public_path('css/emails/vaudreuil.css'));
        }
        $emogrifier->setHtml($html);
        $emogrifier->setCss($css);
        $view->content = $emogrifier->emogrify();
    }

    protected function getFields($bubble, $data, $fields = [])
    {
        $items = [];
        $bubbleType = $bubble->bubbleType();
        $bubbleTypeFields = $bubbleType->getFields();
        foreach ($bubbleTypeFields as $field) {
            $name = $field->name;
            $label = $field->label;
            $type = $field->type;
            if (!in_array($name, $fields)) {
                continue;
            }
            $value = array_get($data, 'fields.' . $name, null);
            if ($value) {
                if ($type === 'date') {
                    $format = isset($field->format) ? $field->format : '%e %B %Y';
                    $value = Str::formatDate($value, $format);
                } elseif ($type === 'daterange') {
                    $value = Str::formatDaterange($value['start'], $value['end']);
                } elseif ($type === 'location') {
                    $value = array_get($value, 'name');
                } elseif (array_get($value, '0.name')) {
                    $value = array_pluck($value, 'name');
                } elseif (array_get($value, 'name')) {
                    $value = array_get($value, 'name');
                }

                $value = trim(is_array($value) ? implode(PHP_EOL, $value) : $value);
                if (!empty($value)) {
                    $items[$name] = [
                        'label' => $label,
                        'value' => $value,
                    ];
                }
            }
        }

        return $items;
    }
}
