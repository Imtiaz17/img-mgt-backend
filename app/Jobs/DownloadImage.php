<?php

namespace App\Jobs;

use App\Models\Media;
use App\Models\User;
use App\Notifications\ImageDownloadNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DownloadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;
    public $user_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, $user_id)
    {
        $this->url = $url;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try {

            //get image from url and upload to local server
            $url = $this->url;
            $contents = file_get_contents($url,true);
            $name = substr($url, strrpos($url, '/') + 1);
            Storage::disk('uploads')->put($name, $contents);

            //save data in database

            $media = new Media();
            $media->user_id = $this->user_id;
            $media->img_name = $name;
            $media->url = $url;
            $media->save();
           
            $user=User::find($this->user_id);

            $user->notify(new ImageDownloadNotification($media));

            DB::commit();
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'errmsg' => $e->getMessage(), 'line' => $e->getLine()], 500);
        }
    }
}
