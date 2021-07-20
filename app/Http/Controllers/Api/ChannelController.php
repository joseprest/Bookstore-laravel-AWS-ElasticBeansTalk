<?php namespace Manivelle\Http\Controllers\Api;

use Illuminate\Http\Request;
use Manivelle\Http\Controllers\Controller;

use Manivelle\Models\Channel;
use Manivelle;

class ChannelController extends Controller
{
    public function csv(Request $request, $id)
    {
        set_time_limit(60 * 5);
        ini_set('memory_limit', '512M');
        $channel = Channel::findOrFail($id);
        $bubbleType = Manivelle::bubbleType($channel->getChannelType()->bubbleType)->toArray();

        $csvPath = tempnam(sys_get_temp_dir(), 'csv_'.$id);
        $fp = fopen($csvPath, 'w');

        $cols = [];
        foreach ($bubbleType['fields'] as $field) {
            $cols[] = $field['name'];
        }
        fputcsv($fp, $cols);

        $page = 1;
        do {
            $bubblesPage = $channel->bubbles()->simplePaginate(100, ['*'], 'page', $page);
            $items = array_get($bubblesPage->toArray(), 'data', []);
            $rows = array_map(function ($item) use ($cols) {
                $row = [];
                foreach ($cols as $col) {
                    $value = array_get($item, 'fields.'.$col, null);
                    if (is_string($value)) {
                        $row[] = $value;
                    } elseif (is_array($value)) {
                        $name = array_get($value, 'name', null);
                        $arrayName = array_get($value, '0.name', null);
                        if (!is_null($name)) {
                            $row[] = $name;
                        } elseif (!is_null($arrayName)) {
                            $row[] = implode(',', array_map(function ($val) {
                                return $val['name'];
                            }, $value));
                        } else {
                            $row[] = '';
                        }
                    } else {
                        $row[] = '';
                    }
                }
                return $row;
            }, $items);

            foreach ($rows as $row) {
                fputcsv($fp, $row);
            }

            $page++;
        } while (sizeof($items) > 0);

        fclose($fp);

        return response()->download($csvPath, str_slug($channel->snippet->title).'.csv');
    }
}
