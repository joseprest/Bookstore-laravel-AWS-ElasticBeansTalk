<?php

namespace Manivelle\Sources\Cairn\Jobs;

use Manivelle\Support\SyncJob;
use Manivelle\Jobs\CreateImagesJob;
use GuzzleHttp\Client as HttpClient;

use Panneau;
use DB;
use Exception;
use Panneau\Exceptions\ResourceNotFoundException;
use Carbon\Carbon;
use Illuminate\Log\Writer;
use Manivelle\Sources\Job;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class SyncCairn extends Job
{
    protected $shouldNeverSkip = true;
    protected $resultsPerPage = 100;

    public function sync()
    {
        $sets = config('manivelle.sources.cairn.sets', ['o:86']);
        foreach ($sets as $set) {
            $job = new SyncPage($set);
            $this->dispatch($job);
        }
    }

    protected function request($query = [])
    {
        $url = 'http://oai.cairn.info/oai.php';
        $client = new HttpClient();
        $res = $client->request('GET', $url, [
            'query' => $query
        ]);

        if ($res->getStatusCode() !== 200) {
            throw new Exception('Status code '.$res->getStatusCode());
        }

        $xml = @simplexml_load_string((string)$res->getBody());

        if (!$xml) {
            throw new Exception('Response is not XML');
        }

        if (isset($xml->error)) {
            throw new Exception('Error:'. $xml->error);
        }

        return $xml;
    }

    public function getSourceJobKey()
    {
        return 'cairn';
    }

    protected function getHandleFromRecord($record)
    {
        return 'cairn_'.Str::slug((string)$record->header->identifier);
    }

    protected function getFieldsFromRecord($record)
    {
        $id = (string)$record->header->identifier;
        $dc = $record->metadata->children('http://bibnum.bnf.fr/NS/onix_dc/')->dc;
        $children = $dc->children('http://purl.org/dc/elements/1.1/');
        $onix = $dc->children('http://www.editeur.org/onix/2.1/reference');

        $isbn = null;
        foreach ($children->identifier as $identifier) {
            if (preg_match('/^[0-9]+$/', (string)$identifier)) {
                $isbn = (string)$identifier;
            }
        }

        $fields = [
            'id' => $id,
            'link' => isset($dc->link) && isset($dc->link->target_url) ? (string)$dc->link->target_url : null,
            'cover_front' => $this->getPictureFromOnix($onix),
            'isbn' => $isbn,
            'title' => isset($children->title) ? (string)$children->title : null,
            'summary' => isset($children->description) ? (string)$children->description : null,
            'authors' => $this->getAuthorsFromCreators(isset($children->creator) ? $children->creator : []),
            'publisher' => isset($children->publisher) ? (string)$children->publisher : null,
            'date' => isset($children->date) && (int)$children->date > 0 ? ((int)$children->date.'-01-01') : null,
            'rights' => isset($children->rights) ? (string)$children->rights : null,
            'language' => isset($children->language) ? substr((string)$children->language, 0, 2) : null,
            'collection' => $this->getCollectionFromOnix($onix),
        ];

        $excelFields = $this->getFieldsFromSpreadsheet($fields);

        return array_merge($fields, $excelFields);
    }

    protected function getExcelRow($fields)
    {
        $isbn = trim((string)array_get($fields, 'isbn', ''));
        $title = str_slug(array_get($fields, 'title', ''));
        $author = str_slug(array_get($fields, 'authors.0.name', ''));

        $filename = storage_path('sources/cairn/books.xlsx');
        $reader = ReaderFactory::create(Type::XLSX);
        $reader->open($filename);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $isbn1 = trim((string)array_get($row, 0, ''));
                $isbn2 = trim((string)array_get($row, 1, ''));
                $rowTitle = trim((string)array_get($row, 2, ''));
                $lastName = trim((string)array_get($row, 3, ''));
                $firstName = trim((string)array_get($row, 4, ''));
                $rowName = str_slug($firstName.' '.$lastName);
                if ($isbn1 === $isbn ||
                    $isbn2 === $isbn ||
                    ($rowName === $author && $rowTitle === $title)
                ) {
                    $reader->close();
                    return $row;
                }
            }
        }
        $reader->close();
        return null;

        // $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        // $cacheSettings = array('memoryCacheSize' => '8MB');
        // PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        //
        // $reader = PHPExcel_IOFactory::createReader('Excel2007');
        // $reader->setReadDataOnly(true);
        // $excel = $reader->load($filename);
        // $sheet = $excel->getActiveSheet();
        // $rows = $sheet->getHighestRow(); // e.g. 10
        // $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
        // $cols = PHPExcel_Cell::columnIndexFromString($highestColumn);
        // for ($row = 1; $row <= $rows; $row++) {
        //     $isbn1 = trim((string)$sheet->getCellByColumnAndRow(0, $row)->getValue());
        //     $isbn2 = trim((string)$sheet->getCellByColumnAndRow(1, $row)->getValue());
        //     $rowTitle = trim((string)$sheet->getCellByColumnAndRow(2, $row)->getValue());
        //     $lastName = trim((string)$sheet->getCellByColumnAndRow(3, $row)->getValue());
        //     $firstName = trim((string)$sheet->getCellByColumnAndRow(4, $row)->getValue());
        //     $rowName = str_slug($firstName.' '.$lastName);
        //     if ($isbn1 === $isbn ||
        //         $isbn2 === $isbn ||
        //         ($rowName === $author && $rowTitle === $title)
        //     ) {
        //         $data = [$isbn1, $isbn2, $rowTitle, $lastName, $firstName];
        //         for ($col = 5; $col <= $cols; $col++) {
        //             $data[] = $sheet->getCellByColumnAndRow($col, $row)->getValue();
        //         }
        //         unset($sheet, $excel);
        //         return $data;
        //     }
        // }
        // return null;
    }

    protected function getFieldsFromSpreadsheet($fields)
    {
        $row = $this->getExcelRow($fields);

        if (is_null($row)) {
            $this->output('<comment>Job message:</comment> Not found in excel');
            return [];
        } else {
            $this->output('<info>Job message:</info> Found in excel');
        }

        $categories = [];
        if (!empty(array_get($row, 7))) {
            $categories[] = [
                'id' => str_slug(array_get($row, 7)),
                'name' => array_get($row, 7),
            ];
        }
        if (!empty(array_get($row, 8))) {
            $categories[] = [
                'id' => str_slug(array_get($row, 8)),
                'name' => array_get($row, 8),
            ];
        }

        $collection = array_get($row, 6);

        $author = [
            'firstname' => array_get($row, 4, ''),
            'lastname' => array_get($row, 3, ''),
            'name' => array_get($row, 4, '').' '.array_get($row, 3, '')
        ];

        $newFields = [
            'categories' => $categories,
            'link' => array_get($row, 14),
            'authors' => [
                $author
            ],
        ];

        if (!empty($collection)) {
            $newFields['collection'] = [
                'id' => str_slug($collection),
                'name' => $collection,
            ];
        }
        return $newFields;
    }

    protected function getCollectionFromOnix($onix)
    {
        if (!isset($onix->Product) || !isset($onix->Product->Series)) {
            return null;
        }
        foreach ($onix->Product->Series as $serie) {
            $title = isset($serie->TitleOfSeries) ? (string)$serie->TitleOfSeries : null;
            if (!empty($title)) {
                return [
                    'id' => str_slug($title),
                    'name' => $title,
                ];
            }
        }
        return null;
    }

    protected function getPictureFromOnix($onix)
    {
        if (!isset($onix->Product) || !isset($onix->Product->MediaFile)) {
            return null;
        }
        $image = null;
        $isBest = false;
        foreach ($onix->Product->MediaFile as $file) {
            $code = (string)$file->MediaFileTypeCode;
            if ($code === '06') {
                $image = (string)$file->MediaFileLink;
                $isBest = true;
            } elseif ($code === '05' && !$isBest) {
                $image = (string)$file->MediaFileLink;
            } elseif ($code === '04' && !$isBest) {
                $image = (string)$file->MediaFileLink;
            }
        }
        return $image;
    }

    protected function getAuthorsFromCreators($creators)
    {
        $authors = [];
        foreach ($creators as $creator) {
            $parts = explode(',', $creator, 2);
            if (sizeof($parts) === 2) {
                $authors[] = [
                    'firstname' => trim($parts[1]),
                    'lastname' => trim($parts[0]),
                    'name' => trim($parts[1]).' '.trim($parts[0])
                ];
            } else {
                $authors[] = [
                    'name' => $creator
                ];
            }
        }

        return $authors;
    }
}
