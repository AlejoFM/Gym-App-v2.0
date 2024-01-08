<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Service extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name : The name of the service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $className = ucfirst($name);

        $path = app_path("Services/{$className}Service.php");
        if (File::exists($path)) {
            $this->error('Service already exists!');
            return 1;
        }

        $content =
            "<?php
namespace App\Services;
use App\Repositories\\{$className}Repository;

class {$className}Service{
    protected \${$name}Repository;
    public function __construct({$className}Repository \${$name}Repository)
    {
        \$this->{$name}Repository = \${$name}Repository;
    }
  }\n";

        File::put($path, $content);

        $this->info('Repository created successfully.');

        return 0;
    }
}
