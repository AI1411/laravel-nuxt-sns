<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $design;

    public function __construct(Design $design)
    {
        $this->design = $design;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = $this->design->disk;
        $filename = $this->design->image;
        $original_file = storage_path() . '/uploads/original/' . $filename;

        try {
            Image::make($original_file)
                ->fit(800, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($large = storage_path('uploads/large/' . $filename));

            // サムネイル用にサイズを変更
            Image::make($original_file)
                ->fit(250, 200, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($thumbnail = storage_path('uploads/thumbnail/' . $filename));

            //それぞれのサイズごとに異なるディレクトリへ保存
            if (Storage::disk($disk)->put('uploads/designs/original/' . $filename, fopen($original_file, 'r+'))) {
                File::delete($original_file);
            }
            if (Storage::disk($disk)->put('uploads/designs/large/' . $filename, fopen($large, 'r+'))) {
                File::delete($large);
            }
            if (Storage::disk($disk)->put('uploads/designs/thumbnail/' . $filename, fopen($thumbnail, 'r+'))) {
                File::delete($thumbnail);
            }

            $this->design->update([
                'upload_successful' => true,
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
