<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DownloadProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:download-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download product images from Unsplash';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to download product images...');
        
        require_once base_path('download_images.php');
        
        $this->info('Product images downloaded successfully!');
        
        return Command::SUCCESS;
    }
} 