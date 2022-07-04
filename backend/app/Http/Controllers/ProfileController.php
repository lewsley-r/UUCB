<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Log;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function fileUpload(Request $req)
    {
        //Conditional logic to determine files destination directory
        if($req->file() && $req->input('filetype') == 'photo' ) 
        {
            $dir = 'user_id_'.$req->input('userId').'/photos/';
            $fileName = time().'_'.$req->file->getClientOriginalName();
            // save file to azure blob directory
            $filePath = $req->file('file')->storeAs($dir, $fileName, 'azure');
            return 'Upload Success';
        }
        elseif($req->file() && $req->input('filetype') == 'profilePhoto' ) 
        {
            $dir = 'user_id_'.$req->input('userId');
            $fileName = 'current_profile_picture';
            // save file to azure blob directory
            $filePath = $req->file('file')->storeAs($dir, $fileName, 'azure');
            return 'Upload Success';
        }
    }

    public function fileReceive(Request $req){
        $receiver = new FileReceiver("file", $req, HandlerFactory::classFromRequest($req));
        $user = $req->input('userId');
        $dir = 'user_id_'.$req->input('userId').'/videos/';

        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        // receive the file
        $save = $receiver->receive();

        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
            // save the file and return any response you need, current example uses `move` function. If you are
            // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
            return $this->saveFileToAzure($save->getFile(), $dir);
        }

        // we are in chunk mode, lets send the current progress
        /** @var AbstractHandler $handler */
        $handler = $save->handler();

        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }

    protected function saveFileToAzure($file, $dir)
    {
        $fileName = $this->createFilename($file);

        $disk = Storage::disk('azure');
        $disk->putFileAs($dir, $file, $fileName);

        // for older laravel
        // $disk->put($fileName, file_get_contents($file), 'public');
        $mime = str_replace('/', '-', $file->getMimeType());

        // We need to delete the file when uploaded to azure
        unlink($file->getPathname());

        return response()->json([
            'path' => $disk->url($fileName),
            'name' => $fileName,
            'mime_type' =>$mime
        ]);
    }

    protected function createFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace(".".$extension, "", $file->getClientOriginalName()); // Filename without extension

        // Add timestamp hash to name of the file
        $filename .= "_" . md5(time()) . "." . $extension;

        return $filename;
    }

    public function getProfilePicture(Request $req)
    {
        Log::info($req);
        $dir = 'user_id_'.$req->input('userId').'/'.'current_profile_picture';
        $disk = Storage::disk('azure');
        Log::info($dir);
        $url = $disk->url($dir);
        return $url;
    }

    public function getPhotos(Request $req)
    {
        Log::info($req);
        $dir = 'user_id_'.$req->input('userId').'/photos';
        $disk = Storage::disk('azure');
        $files = $disk->allFiles($dir);
        return $files;
    }

    public function getVideos(Request $req)
    {
        Log::info($req);
        $dir = 'user_id_'.$req->input('userId').'/videos';
        $disk = Storage::disk('azure');
        $files = $disk->allFiles($dir);
        return $files;
    }

    public function followUser(Request $req)
    {
        $followUser = User::find($req->input('profileId'));
        $currentUser = User::find($req->input('userId'));
        if(! $followUser) 
        {
            return ('User does not exist.'); 
        }
        $followUser->followers()->attach($currentUser);
        return ('Successfully followed user.');
    }

    public function unfollowUser(Request $req)
    {
        $followUser = User::find($req->input('profileId'));
        $currentUser = User::find($req->input('userId'));
        if(! $followUser) 
        {
            return ('User does not exist.'); 
        }
        $followUser->followers()->detach($currentUser);
        return ('Successfully unfollowed user.');
    }

    public function getFollowers(Request $req)
    {
        $user = User::find($req->input('profileId'));
        $followers = $user->followers;
        return $followers;
    }
    public function getFollowings(Request $req)
    {
        $user = User::find($req->input('profileId'));
        $followings = $user->followings;
        return $followings;
    }

    public function getNlpData(Request $req)
    {
        $response = Http::post('https://europe-west2-uucb-354812.cloudfunctions.net/nlp-func', [
            'message' => $req->message
        ]);
        return $response;
    }

  
}
