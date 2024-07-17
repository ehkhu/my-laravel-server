<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeMyController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:make-my-controller';
    protected $signature = 'make:mycontroller {name} {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a controller with a predefined template';


    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $model = $this->option('model');

        if (!$model) {
            $this->error('Please provide a model using --model option.');
            return;
        }

        $controllerPath = app_path("Http/Controllers/Api/{$name}Controller.php");

        // Check if the file already exists
        if (File::exists($controllerPath)) {
            $this->error("Controller {$name}Controller already exists!");
            return;
        }

        // Create the directory if it doesn't exist
        if (!File::isDirectory(app_path('Http/Controllers/Api'))) {
            File::makeDirectory(app_path('Http/Controllers/Api'), 0755, true);
        }

        // Generate the controller content
        $controllerTemplate = $this->generateControllerTemplate($name, $model);

        // Write the content to the file
        File::put($controllerPath, $controllerTemplate);

        $this->info("Controller {$name}Controller created successfully.");
    }

    protected function generateControllerTemplate($name, $model)
    {
        $camelCaseModel = lcfirst($model);
        $camelCaseRepository = lcfirst($model) . 'Repository';

        return <<<EOT
        <?php

        namespace App\Http\Controllers\Api;

        use App\Http\Controllers\Controller;
        use App\Repositories\Interfaces\\{$model}RepositoryInterface;
        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Validator;

        class {$name}Controller extends Controller
        {
            protected \${$camelCaseRepository};

            public function __construct({$model}RepositoryInterface \${$camelCaseRepository})
            {
                \$this->{$camelCaseRepository} = \${$camelCaseRepository};
            }

            public function index(Request \$request)
            {
                try {
                    \${$camelCaseModel}s = \$this->{$camelCaseRepository}->all(\$request);
                    return response()->json(['data' => \${$camelCaseModel}s]);
                } catch (\Exception \$e) {
                    \Log::error(\$e);
                    return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
                }
            }

            public function show(\$id)
            {
                try {
                    \${$camelCaseModel} = \$this->{$camelCaseRepository}->find(\$id);

                    if (!\${$camelCaseModel}) {
                        return response()->json(['error' => ['code' => 404, 'message' => '{$model} not found']], 404);
                    }

                    return response()->json(['data' => \${$camelCaseModel}]);
                } catch (\Exception \$e) {
                    \Log::error(\$e);
                    return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
                }
            }

            public function store(Request \$request)
            {
                try {
                    \$validator = Validator::make(\$request->all(), [
                        // Add validation rules here
                    ]);
                    if (\$validator->fails()) {
                        return response()->json(['error' => ['code' => 422, 'message' => 'Validation failed', 'errors' => \$validator->errors()]], 422);
                    }

                    \${$camelCaseModel} = \$this->{$camelCaseRepository}->create(\$request->all());

                    return response()->json(['data' => \${$camelCaseModel}], 201);
                } catch (\Exception \$e) {
                    \Log::error(\$e);
                    return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
                }
            }

            public function update(Request \$request, \$id)
            {
                try {
                    \$validator = Validator::make(\$request->all(), [
                        // Add validation rules here
                    ]);

                    if (\$validator->fails()) {
                        return response()->json(['error' => ['code' => 422, 'message' => 'Validation failed', 'errors' => \$validator->errors()]], 422);
                    }

                    \${$camelCaseModel} = \$this->{$camelCaseRepository}->update(\$id, \$request->all());

                    return response()->json(['data' => \${$camelCaseModel}]);
                } catch (\Exception \$e) {
                    \Log::error(\$e);
                    return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
                }
            }

            public function destroy(\$id)
            {
                try {
                    \$this->{$camelCaseRepository}->delete(\$id);

                    return response()->json(['message' => '{$model} deleted successfully']);
                } catch (\Exception \$e) {
                    \Log::error(\$e);
                    return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
                }
            }

            public function values(){
                try {
                    \${$camelCaseModel}s = \$this->{$camelCaseRepository}->values();
                    return response()->json(['data' => \${$camelCaseModel}s]);
                } catch (\Exception \$e) {
                    \Log::error(\$e);
                    return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
                }
            }

            //overview
            public function getCount{$model}(){
                try {
                    \${$camelCaseModel}s = \$this->{$camelCaseRepository}->count{$model}s();
                    return response()->json(['data' => \${$camelCaseModel}s]);
                } catch (\Exception \$e) {
                    \Log::error(\$e);
                    return response()->json(['error' => ['code' => 500, 'message' => 'Internal Server Error']], 500);
                }
            }
        }
        EOT;
    }
}
