<?php

namespace Manivelle\Banq\Sources\Jobs;

use Manivelle\Models\Bubble;
use Manivelle\Models\Channel;
use Illuminate\Log\Writer;

class SyncBanqPhotosItem extends SyncBanqPhotos
{
    public $item;
    public $isLast;

    protected $shouldNeverSkip = false;

    public function __construct($item, $isLast = false)
    {
        $this->item = $item;
        $this->isLast = $isLast;
    }

    public function getSourceJobKey()
    {
        $key = parent::getSourceJobKey();
        $id = array_get($this->item, 'id');
        return $key.'_photo_'.$id;
    }

    public function sync()
    {
        $id = array_get($this->item, 'id');

        //Check images
        $imagette = $this->getImageFromItem($this->item);
        if (!$this->imageIsValid($imagette)) {
            return;
        }

        //Skip if type is not right
        $handle = $this->getHandleFromItem($this->item);
        if ($this->requestOptions['type'] === 'Images' && strpos(array_get($this->item, 'doctype', ''), 'postales') !== false) {
            return;
        }

        $fields = $this->getFieldsFromItem($this->item);
        if ($fields) {
            $bubble = $this->createBubble($handle, $fields);

            if ($bubble) {
                $this->addBubbleToChannels($bubble);
            }
        }
    }

    protected function getImageFromItem($item)
    {
        $url = array_get($item, 'url');
        if (!empty($url) && preg_match('/ark\:\/([0-9]+)\/([0-9]+)$/', $url, $matches)) {
            $handle = $matches[1].'/'.$matches[2];
            $notice = $this->requestNotice($handle);
            if ($notice) {
                $fields['publisher'] = array_get($notice, 'editeur.0', '');
                $medias = array_get($notice, 'bitstreams.liste', []);
                foreach ($medias as $media) {
                    $file = array_get($media, 'fichier');
                    $mime = array_get($media, 'mimeType');
                    $url = array_get($media, 'url');
                    if (!empty($url) &&
                        (preg_match('/\.(jpg|png|jpeg|gif)/', $file) || preg_match('/^image\//', $mime))) {
                        return $url;
                    }
                }
            }
        }

        $imagette = array_get($item, 'imagette');
        if (!empty($imagette) &&
            (
                preg_match('/retrieve\/([0-9]+)$/', $imagette) ||
                preg_match('/\.(jpg|png)$/i', $imagette) ||
                preg_match('/isbn\=/i', $imagette)
            )
        ) {
            return $imagette;
        }

        return null;
    }

    protected function bubbleHasChanged($bubble, $fields)
    {
        if ($bubble->fields->image) {
            $currentImage = $bubble->fields->image->name;
            if (!preg_match('/'.preg_quote($currentImage).'/', $fields['image'])) {
                return true;
            }
        }

        $fieldsToCheck = ['title', 'description', 'author', 'date', 'dateText', 'physical_description', 'link'];
        foreach ($fieldsToCheck as $field) {
            $value = array_get($fields, $field);
            $currentValue = $bubble->fields->{$field};
            if ($currentValue !== $value) {
                return true;
            }
        }

        return false;
    }
}
