<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PrepareStorage extends Command
{
    protected $signature = 'app:prepare-storage';
    protected $description = 'Create storage folders and copy default files';

    public function handle()
    {
        // Buat folder jika belum ada
        Storage::makeDirectory('public/icons');
        Storage::makeDirectory('public/profile_photos');

        // Copy icons
        $iconSourcePath = resource_path('icons');
        $iconTargetPath = storage_path('app/public/icons');
        File::copyDirectory($iconSourcePath, $iconTargetPath);
        $this->info("Copied icons to $iconTargetPath");

        // Copy profile photos
        $profileSourcePath = resource_path('profile_photos');
        $profileTargetPath = storage_path('app/public/profile_photos');
        File::copyDirectory($profileSourcePath, $profileTargetPath);
        $this->info("Copied profile photos to $profileTargetPath");
    }
}
