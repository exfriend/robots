<?php

namespace Exfriend\Robots\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

class MakeScraperCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:robots:scraper';
    protected $namespace = 'App\\Robots';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the whole scraper interactively';

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

    public function fire()
    {

        $this->call('make:robots:robot', ['name' => $this->argument('name')]);

        if ($this->confirm('Register it in Robots\Kernel?', 'yes')) {
            $this->addRobotToKernel($this->argument('name'));
        }

        if ($this->confirm('Do you wish to define requests?', 'yes')) {
            $this->makeRequest();
            while ($this->confirm('Add another request? [y|N]')) {
                $this->makeRequest();
            }
        }

        if ($this->confirm('Do you wish to define parsers?', 'yes')) {
            $this->makeParser();
            while ($this->confirm('Add another parser?')) {
                $this->makeParser();
            }
        }

        if ($this->confirm('Do you wish to define data objects?', 'yes')) {
            $this->makeDTO();
            while ($this->confirm('Add another DTO?')) {
                $this->makeDTO();
            }
        }

    }

    public function addRobotToKernel($name)
    {

        $file = file_get_contents(app_path('Robots/Kernel.php'));
        $file = str_replace('protected $robots = [',
            'protected $robots = [' . PHP_EOL . "\t\t" .
            "'" . strtolower($name) . "' => \\App\\Robots\\" . ucfirst($name) . "\\Robot::class,",
            $file);

        file_put_contents(app_path('Robots/Kernel.php'), $file);

    }

    public function makeRequest()
    {
        $name = $this->ask('Enter the name of the request');

        $args = [
            '--robot' => $this->argument('name'),
            'name' => $name,
        ];

        if ($url = $this->ask('Enter URL or leave empty', 'http://')) {
            $args [ '--url' ] = $url;
        }
        $this->call('make:robots:request', $args);
    }

    public function makeParser()
    {
        $name = $this->ask('Enter the name of the parser', 'Parser');
        $this->call('make:robots:parser', [
            '--robot' => $this->argument('name'),
            'name' => $name,
        ]);
    }

    public function makeDTO()
    {
        $name = $this->ask('Enter the name of the DTO');
        $this->call('make:robots:dto', [
            '--robot' => $this->argument('name'),
            'name' => $name,
        ]);
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the robot'],
        ];
    }

}
