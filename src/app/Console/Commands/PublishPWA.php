<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishPWA extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pwa:publish-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish PWA-related files: manifest.json, offline.html, and service-worker.js.';

    /**
     * Composer instance for autoload generation.
     *
     * @var \Illuminate\Foundation\Composer
     */
    protected $composer;

    /**
     * Constructor to initialize the command.
     */
    public function __construct() {
        parent::__construct();

        $this->composer = app()['composer'];
    }

    /**
     * Execute the console command.
     */
    public function handle() {
        $updatedManifest = $this->generateManifestData();
        // $source = resource_path('pwa-stubs');
        $publicDirectory = public_path();

        // Process manifest.json
        $this->processManifestFile($publicDirectory, $updatedManifest);

        // Process offline.html
        $this->publishFile('offline.html', $publicDirectory);

        // Process service-worker.js
        $this->publishFile('service-worker.js', $publicDirectory);

        // // Regenerate autoload files
        // $this->info('Generating autoload files');
        // $this->composer->dumpOptimized();
        $this->info('✔');
    }

    /**
     * Generate manifest data from configuration.
     *
     * @return array
     */
    protected function generateManifestData() {
        return [
            'name' => config('pwa.manifest.name'),
            'short_name' => config('pwa.manifest.short_name'),
            'start_url' => config('pwa.manifest.start_url'),
            'display' => config('pwa.manifest.display'),
            'theme_color' => config('pwa.manifest.theme_color'),
            'background_color' => config('pwa.manifest.background_color'),
            'icons' => config('pwa.manifest.icons'),
            'orientation' => config('pwa.manifest.orientation'),
            'status_bar' => config('pwa.manifest.status_bar'),
            'splash' => config('pwa.manifest.splash'),
        ];
    }

    /**
     * Publish a specific file from stubs to the public directory.
     *
     * @param string $fileName
     * @param string $publicDirectory
     */
    protected function publishFile(string $fileName, string $publicDirectory) {
        $stubPath = resource_path("pwa-stubs/{$fileName}");

        if (!File::exists($stubPath)) {
            $this->error("{$fileName} stub file not found.");
            return;
        }

        // $fileContent = file_get_contents($stubPath);
        $fileContent = File::get($stubPath);
        $this->createOrUpdateFile($publicDirectory, $fileName, $fileContent);
        $this->info("{$fileName} file has been published.");
    }

    /**
     * Process the manifest.json file.
     *
     * @param string $publicDirectory
     * @param array $updatedManifest
     */
    protected function processManifestFile(string $publicDirectory, array $updatedManifest) {
        $manifestStubPath = resource_path('pwa-stubs/manifest.json');

        if (!File::exists($manifestStubPath)) {
            $this->error('manifest.json stub file not found.');
            return;
        }

        $manifestContent = json_decode(File::get($manifestStubPath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('The manifest.json file contains invalid JSON.');
            return;
        }

        $this->info('Current manifest.json content:');
        $this->line(json_encode($manifestContent, JSON_PRETTY_PRINT));

        $this->info('Updated manifest.json content:');
        $this->line(json_encode($updatedManifest, JSON_PRETTY_PRINT));

        // $confirm = $this->ask('Do you wish to override the existing manifest.json? (Y/N)', 'Y');
        if ($this->confirm('Do you wish to override the existing manifest.json?', true)) {
            foreach($manifestContent as $k => $v) {
                $manifestContent[$k] = config("pwa.manifest.{$k}", $v);
            }
        }

        $updatedManifestContent = json_encode($manifestContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $this->createOrUpdateFile($publicDirectory, 'manifest.json', $updatedManifestContent);
        $this->info('manifest.json file has been published.');
    }

    /**
     * Create or update a file in the specified directory.
     *
     * @param string $directoryPath
     * @param string $fileName
     * @param string $content
     */
    protected function createOrUpdateFile(string $directoryPath, string $fileName, string $content) {
        // if (!file_exists($directoryPath)) {
        //     mkdir($directoryPath, 0755, true);
        // }
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $path = $directoryPath . DIRECTORY_SEPARATOR . $fileName;
        // file_put_contents($path, $content);
        File::put($path, $content);
    }
}
