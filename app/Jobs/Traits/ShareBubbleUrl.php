<?php

namespace Manivelle\Jobs\Traits;

trait ShareBubbleUrl
{
    public function getShareBubbleUrl($bubble, $organisation)
    {
        $url = $bubble->snippet->link;

        if ($bubble->source &&
            $bubble->source->type === 'pretnumerique' &&
            isset($bubble->fields->isbn)
        ) {
            $isbn = $bubble->fields->isbn;
            $slug = $organisation ? data_get($organisation->settings, 'pretnumerique_id', null) : null;
            $url = 'https://'.(!empty($slug) ? $slug : 'banq').'.pretnumerique.ca/resources?utf8=%E2%9C%93&q='.$isbn;
        }

        return $url;
    }


    protected function getPretNumeriqueResource($library, $id)
    {
        $url = 'http://'.$library.'.pretnumerique.ca/resource_entries/'.$id.'.atom';

        $content = @file_get_contents($url);
        $xml = !empty($content) ? @simplexml_load_string($content):null;

        return $xml;
    }

    protected function getResourceISBN($resource)
    {
        $isbn = null;
        foreach ($resource->link as $link) {
            $type = isset($link['type']) ? (string)$link['type'] : null;
            $rel = isset($link['rel']) ? (string)$link['rel'] : null;
            if ($type === 'application/epub+zip') {
                $href = (string)$link['href'];
                if (preg_match('/assets\/publications\/[^\/]+\/medias\/([0-9A-Za-z]+)/', $href, $matches)) {
                    $isbn = $matches[1];
                }
            } elseif ($type === 'text/html' && $rel === 'http://opds-spec.org/acquisition/sample') {
                $href = (string)$link['href'];
                if (preg_match('/media\/[^\-]+\-([^\-]+)\-/', $href, $matches)) {
                    $isbn = $matches[1];
                }
            } elseif ($type === 'text/html' && $rel === 'http://opds-spec.org/acquisition/borrow') {
                $href = (string)$link['href'];
                if (preg_match('/\/bookings\?medium_id\=[^\-]+\-([^\-]+)\-epub/', $href, $matches)) {
                    $isbn = $matches[1];
                }
            }
        }

        return $isbn;
    }
}
