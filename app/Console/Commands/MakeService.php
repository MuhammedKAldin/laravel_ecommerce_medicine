<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $serviceName = ucfirst($name);
        $directory = app_path('Services');
        $path = $directory . "/{$serviceName}.php";

        // Ensure the directory exists
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Check if service already exists
        if (File::exists($path)) {
            $this->error("Service {$serviceName} already exists!");
            return;
        }

        // Service class content
        $stub = "<?php

namespace App\Services;

class {$serviceName}
{
    //
}
";

        File::put($path, $stub);

        $this->info("Service {$serviceName} created successfully at Services/{$serviceName}.php");
    }
}
