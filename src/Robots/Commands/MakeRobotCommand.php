<?php

namespace Exfriend\Robots\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeRobotCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:robots:robot';
    protected $namespace = 'App\\Robots';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new robot';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = app()[ 'composer' ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->makeRobot();
    }

    /**
     * Generate the desired migration.
     */
    protected function makeRobot()
    {
        $name = ucfirst($this->argument('name'));

        if ($this->files->exists($path = $this->getPath($name))) {
            return $this->error('File ' . $name . ' already exists!');
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->compileStub());

        $this->info('Robot created successfully.');

        $this->composer->dumpAutoloads();
    }

    /**
     * Get the path to where we should store the migration.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        return app_path() . '/Robots/' . $name . '/Robot.php';
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileStub()
    {
        $stub = $this->files->get(__DIR__ . '/../../stubs/robot.stub');

        $this->replaceClassName($stub);
        $this->replaceNamespace($stub);

        return $stub;
    }

    /**
     * Replace the class name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceClassName(&$stub)
    {
        $className = 'Robot';

        $stub = str_replace('{{class}}', $className, $stub);

        return $this;
    }

    protected function replaceNamespace(&$stub)
    {
        $namespace = $this->namespace;
        $namespace .= '\\' . ucwords($this->argument('name'));

        $stub = str_replace('{{namespace}}', $namespace, $stub);

        $stub = str_replace('{{url}}', $this->option('url') ?? '', $stub);
        return $this;
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the robot'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['url', 'u', InputOption::VALUE_OPTIONAL, 'Base URL e.g. http://site.com'],
        ];
    }

}
