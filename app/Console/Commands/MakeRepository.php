<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name : The name of the repository and interface}';
    protected $description = 'Create a new interface and repository';

    protected Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        $name = $this->argument('name');

        // Define file paths for interface and repository
        $interfacePath = app_path("Repositories/Contracts/{$name}Interface.php");
        $repositoryPath = app_path("Repositories/{$name}Repository.php");

        // Create directories if they do not exist
        $this->makeDirectory($interfacePath);
        $this->makeDirectory($repositoryPath);

        // Create the interface file
        $this->filesystem->put($interfacePath, $this->getInterfaceTemplate($name));
        $this->info("Interface created at: {$interfacePath}");

        // Create the repository file
        $this->filesystem->put($repositoryPath, $this->getRepositoryTemplate($name));
        $this->info("Repository created at: {$repositoryPath}");
    }

    protected function makeDirectory($path)
    {
        if (!$this->filesystem->isDirectory(dirname($path))) {
            $this->filesystem->makeDirectory(dirname($path), 0755, true);
        }
    }

    protected function getInterfaceTemplate($name)
    {
        return <<<EOT
<?php

namespace App\Repositories\Contracts;

interface {$name}Interface
{
    // Define the methods that the repository will implement
    public function all();
    public function find(\$id);
    public function create(array \$data);
    public function update(\$id, array \$data);
    public function delete(\$id);
}
EOT;
    }

    protected function getRepositoryTemplate($name)
    {
        return <<<EOT
<?php

namespace App\Repositories;

use App\Repositories\Contracts\\{$name}Interface;

class {$name}Repository implements {$name}Interface
{
    // Implement the methods from the interface
    public function all()
    {
        // Logic for getting all items
    }

    public function find(\$id)
    {
        // Logic for finding a single item
    }

    public function create(array \$data)
    {
        // Logic for creating a new item
    }

    public function update(\$id, array \$data)
    {
        // Logic for updating an item
    }

    public function delete(\$id)
    {
        // Logic for deleting an item
    }
}
EOT;
    }
}
