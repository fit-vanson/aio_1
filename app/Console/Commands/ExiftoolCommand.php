<?php

namespace App\Console\Commands;

use App\Models\Exiftool;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExiftoolCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exiftool:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = Exiftool::where('updated_at','<', Carbon::now())->get();
        foreach ($files as $file){
            $file->delete();
        }
    }


}
