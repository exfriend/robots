<?php

namespace Exfriend\Robots\Generators\Robot;

use Illuminate\Console\Command as LaravelCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Command extends LaravelCommand
{
    protected $name = 'make:robots:robot';
    protected $namespace = 'App\\Robots';

    protected $description = 'Create a new robot';
    /**
     * @var Generator
     */
    private $generator;

    public function __construct( Generator $generator )
    {
        $this->generator = $generator;
    }

    public function fire()
    {
        $name = strtolower( $this->argument( 'name' ) );
        if ( !$url = !empty( $this->option( 'url' ) ) ? $this->option( 'url' ) : false )
        {
            $url = $this->ask( 'Enter URL:', 'http' );
        }

        $this->generator->generate( $name, [
            'class' => 'Robot',
            'url' => $url,
        ] );

        $this->info( 'Robot created successfully.' );
    }

    protected function getArguments()
    {
        return [
            [ 'name', InputArgument::REQUIRED, 'The name of the robot, lowercase' ],
        ];
    }

    protected function getOptions()
    {
        return [
            [ 'url', 'u', InputOption::VALUE_OPTIONAL, 'Base URL e.g. http://site.com' ],
        ];
    }

}
