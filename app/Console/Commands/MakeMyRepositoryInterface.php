<?php
// app/Console/Commands/MakeMyRepositoryInterface.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File; // Import the File facade
use Illuminate\Support\Str;

class MakeMyRepositoryInterface extends Command
{
    protected $signature = 'make:myrepositoryinterface {name}';
    protected $description = 'Create a repository interface with a predefined template';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $interfacePath = app_path("Repositories/Interfaces/{$name}RepositoryInterface.php");

        // Check if the file already exists
        if (File::exists($interfacePath)) {
            $this->error("Repository interface {$name}RepositoryInterface already exists!");
            return;
        }

        // Create the directory if it doesn't exist
        if (!File::isDirectory(app_path('Repositories/Interfaces'))) {
            File::makeDirectory(app_path('Repositories/Interfaces'), 0755, true);
        }

        // Generate the interface content
        $interfaceTemplate = $this->generateInterfaceTemplate($name);

        // Write the content to the file
        File::put($interfacePath, $interfaceTemplate);

        $this->info("Repository interface {$name}RepositoryInterface created successfully.");
    }

    protected function generateInterfaceTemplate($name)
    {
        return <<<EOT
        <?php
        namespace App\Repositories\Interfaces;

        interface {$name}RepositoryInterface
        {
            public function all(\$request);

            public function find(\$id);

            public function create(array \$data);

            public function update(\$id, array \$data);

            public function delete(\$id);

            public function get{$name}sAsValueLabel();

            public function count{$name}s();
        }
        EOT;
    }
}