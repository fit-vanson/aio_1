<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class DeleteFileBuild implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $folder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($folder)
    {
        $this->folder = $folder;

    }

    /**
     * Execute the job.
     *
     * @param $folder
     * @return void
     */
    public function handle()
    {
        File::deleteDirectory($this->folder);
    }
}
