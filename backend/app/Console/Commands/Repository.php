<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Repository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name : The name of the repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $className = ucfirst($name);

        $path = app_path("Repositories/{$className}Repository.php");
        if (File::exists($path)) {
            $this->error('Repository already exists!');
            return 1;
        }

        $content =
            "<?php
namespace App\Repositories;
use App\Models\\$className;

class {$className}Repository{
    protected \${$name};
    public function __construct({$className} \${$name})
    {
        \$this->{$name} = \${$name};
    }
  }\n";

    File::put($path, $content);

        $this->info('Repository created successfully.');

        return 0;
    }
}
