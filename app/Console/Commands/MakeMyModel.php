<?php
// app/Console/Commands/MakeMyModel.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File; // Import the File facade
use Illuminate\Support\Str;

class MakeMyModel extends Command
{
    protected $signature = 'make:mymodel {name}';
    protected $description = 'Create a model with a predefined template';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $modelPath = app_path("Models/{$name}.php");

        // Check if the file already exists
        if (File::exists($modelPath)) {
            $this->error("Model {$name} already exists!");
            return;
        }

        // Create the directory if it doesn't exist
        if (!File::isDirectory(app_path('Models'))) {
            File::makeDirectory(app_path('Models'), 0755, true);
        }

        // Generate the model content
        $modelTemplate = $this->generateModelTemplate($name);

        // Write the content to the file
        File::put($modelPath, $modelTemplate);

        $this->info("Model {$name} created successfully.");
    }

    protected function generateModelTemplate($name)
    {
        return <<<EOT
        <?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Model;
        use Carbon\Carbon;

        class {$name} extends Model
        {
            use HasFactory;
            protected \$fillable = [
                // Define fillable attributes here
            ];

            protected \$hidden = [
                'created_at',
                'updated_at',
            ];

            protected static function boot()
            {
                parent::boot();

                static::saving(function (\$model) {
                    // Format the date attribute before saving
                });
            }

            public function scopeFilter(\$query, array \$filters)
            {
                \$query->when(\$filters['search'] ?? false, fn(\$query, \$search) =>
                    \$query->where('name', 'like', '%' . \$search . '%')
                );
            }
        }
        EOT;
    }
}