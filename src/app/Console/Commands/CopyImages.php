<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CopyImages extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:copy-images';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy images from resources/img to public/img';

    /**
     * Execute the console command.
     */
    public function handle() {
        //
        $source = resource_path('img');
        $destination = public_path('img');

        if (!File::exists($source)) {
            $this->error("Source directory doesn't exist: $source");
            return;
        }

        File::ensureDirectoryExists($destination);
        File::copyDirectory($source, $destination);

        $this->info('✔ Images copied from resources/img to public/img');
    }
}
