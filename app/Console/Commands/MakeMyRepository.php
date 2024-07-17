<?php
// app/Console/Commands/MakeMyRepository.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File; // Import the File facade
use Illuminate\Support\Str;

class MakeMyRepository extends Command
{
    protected $signature = 'make:myrepository {name}';
    protected $description = 'Create a repository implementation with a predefined template';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $repositoryPath = app_path("Repositories/Eloquent/{$name}Repository.php");

        // Check if the file already exists
        if (File::exists($repositoryPath)) {
            $this->error("Repository {$name}Repository already exists!");
            return;
        }

        // Create the directory if it doesn't exist
        if (!File::isDirectory(app_path('Repositories/Eloquent'))) {
            File::makeDirectory(app_path('Repositories/Eloquent'), 0755, true);
        }

        // Generate the repository content
        $repositoryTemplate = $this->generateRepositoryTemplate($name);

        // Write the content to the file
        File::put($repositoryPath, $repositoryTemplate);

        $this->info("Repository {$name}Repository created successfully.");
    }

    protected function generateRepositoryTemplate($name)
    {
        $camelCaseModel = Str::camel($name);
        $snakeCaseModel = Str::snake($name);
        return <<<EOT
        <?php
        namespace App\Repositories\Eloquent;

        use App\Models\\{$name};
        use App\Repositories\Interfaces\\{$name}RepositoryInterface;

        class {$name}Repository implements {$name}RepositoryInterface
        {
            protected \$model;

            public function __construct({$name} \$model)
            {
                \$this->model = \$model;
            }

            public function all(\$request)
            {
                return \$this->model->filter(request(['search']))
                ->orderBy(\$request['sortBy'] ? \$request['sortBy'] : 'id', \$request['direction'] ? \$request['direction'] : "desc")
                ->paginate(\$request['pageSize'] ? \$request['pageSize'] : 10);
            }

            public function find(\$id)
            {
                \$patient = \$this->model->with([
                    // Add relation here
                ])
                ->find(\$id);
                return \$patient;
            }

            public function create(array \$data)
            {
                return \$this->model->create(\$data);
            }

            public function update(\$id, array \$data)
            {
                \$record = \$this->model->findOrFail(\$id);
                \$record->update(\$data);
                return \$record;
            }

            public function delete(\$id)
            {
                \$record = \$this->model->findOrFail(\$id);
                \$record->delete();
            }

            public function get{$name}sAsValueLabel()
            {
                \$data = \$this->model->select('id as value', 'name as label')->get();
                // Convert 'value' to string for each item in the collection
                return \$formattedData = \$data->map(function (\$item) {
                    return [
                        'value' => (string) \$item['value'],
                        'label' => \$item['label'],
                    ];
                });
            }

            public function count{$name}s()
            {
                \$numberOf{$name}s = {$name}::count();
                return ["{$snakeCaseModel}_count" => \$numberOf{$name}s];
            }

            public function getAll()
            {
                return \$this->model->all();
            }
            
            public function exportFilter(\$request)
            {
                return \$this->model->filter(request(['search']))->get();
            }
        }
        EOT;
    }
}