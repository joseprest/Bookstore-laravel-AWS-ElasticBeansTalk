<?php namespace Manivelle\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Folklore\Image\Exception\Exception;
use Folklore\Image\Exception\FileMissingException;
use Folklore\Image\Exception\ParseException;

use App;
use Image;
use Storage;

class ImageController extends BaseController
{

    use DispatchesJobs, ValidatesRequests;
    
    public function serve($path)
    {
        $app = app();
        
        $diskCloud = Storage::disk('s3');
        $diskTemp = Storage::disk('temp');
        
        //Get the full path of an image
        $fullPath = 'images/'.$path;
        
        if ($diskCloud->exists($fullPath)) {
            $contents = $diskCloud->get($fullPath);
            $expires = 3600*24*31;
            $response = response()->make($contents, 200);
            $response->header('Content-Type', 'image/jpeg');
            $response->header('Cache-control', 'max-age='.$expires.', public');
            $response->header('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + $expires));
            
            return $response;
            
            //return redirect()->to('https://s3.amazonaws.com/'.config('filesystems.disks.s3.bucket').'/'.$fullPath);
        } else {
            try {
                $parse = Image::parse($fullPath);
                $originalPath = $parse['path'];
                $tmpPath = config('filesystems.disks.temp.root');
                $tmpOriginalName = 'manivelle_image_original_'.uniqid();
                $tmpTransformedName = 'manivelle_image_transformed_'.uniqid();
                $tmpOriginalPath = $tmpPath.'/'.$tmpOriginalName;
                $tmpTransformedPath = $tmpPath.'/'.$tmpTransformedName;
                
                if (!$diskCloud->exists($originalPath)) {
                    throw new FileMissingException();
                }
                
                $contents = $diskCloud->get($originalPath);
                $diskTemp->put($tmpOriginalName, $contents);
                
                $format = Image::format($tmpOriginalPath);
                switch ($format) {
                    case 'gif':
                        $mime = 'image/gif';
                        break;
                    case 'png':
                        $mime = 'image/png';
                        break;
                    default:
                        $mime = 'image/jpeg';
                        break;
                }
                
                Image::make($tmpOriginalPath, $parse['options'])->save($tmpTransformedPath);
                
                $resource = fopen($tmpTransformedPath, 'r');
                $diskCloud
                    ->getDriver()
                    ->put(
                        $fullPath,
                        $resource,
                        [
                        'visibility' => 'public',
                        'ContentType' => $mime,
                        'CacheControl' => 'max-age='.(3600 * 24)
                        ]
                    );
                fclose($resource);
                
                unlink($tmpOriginalPath);
                unlink($tmpTransformedPath);
                
                $contents = $diskCloud->get($fullPath);
                $response = response()->make($contents, 200);
                $expires = 3600*24*31;
                $response->header('Content-Type', $mime);
                $response->header('Cache-control', 'max-age='.$expires.', public');
                $response->header('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + $expires));
                
                return $response;
            } catch (FileMissingException $e) {
                return abort(404);
            } catch (ParseException $e) {
                return abort(500);
            } catch (\Exception $e) {
                return abort(500);
            }
        }
    }
}
