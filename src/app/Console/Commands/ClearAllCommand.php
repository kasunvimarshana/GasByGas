<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all caches and compiled files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Clearing application caches and compiled files...');

        // Clear application cache
        $this->call('cache:clear');

        // Clear route cache
        $this->call('route:clear');

        // Clear config cache
        $this->call('config:clear');

        // Clear view cache
        $this->call('view:clear');

        // Clear event cache
        $this->call('event:clear');

        // Clear compiled files
        if (file_exists($compiledPath = base_path('bootstrap/cache/compiled.php'))) {
            unlink($compiledPath);
            $this->info('Cleared compiled files.');
        }

        $this->info('All caches and compiled files have been cleared.');
        return Command::SUCCESS;
    }
}
