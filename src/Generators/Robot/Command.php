<?php

namespace Exfriend\Robots\Generators\Robot;

use Exfriend\Robots\Generators\BaseCommand;
use Exfriend\Robots\Generators\Robot\Generator as RobotGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Command extends BaseCommand
{
    protected $name = 'robots:robot';
    protected $namespace = 'App\\Robots';

    protected $description = 'Create a new robot';
    /**
     * @var Generator
     */
    private $generator;

    public function __construct( RobotGenerator $generator )
    {
        $this->generator = $generator;
        parent::__construct();
    }

    public function fire()
    {
        $name = strtolower( $this->argument( 'name' ) );
        $url = $this->askOption( 'url', 'Enter base url', 'http://' );

        $this->generator->generate( $name, [
            'class' => 'Robot',
            'url' => $url,
        ] );

        $this->info( 'Robot created successfully.' );

        if ( $this->confirm( 'Register it in Robots\Kernel?', 'yes' ) )
        {
            $this->addRobotToKernel( $name );
        }
    }

    protected function getArguments()
    {
        return [
            [ 'name', InputArgument::REQUIRED, 'The name of the robot, lowercase e.g. ebay' ],
        ];
    }

    protected function getOptions()
    {
        return [
            [ 'url', 'u', InputOption::VALUE_OPTIONAL, 'Base URL e.g. http://site.com' ],
        ];
    }

    protected function addRobotToKernel( $name )
    {
        $file = file_get_contents( app_path( 'Robots/Kernel.php' ) );
        $file = str_replace( 'protected $robots = [',
            'protected $robots = [' . PHP_EOL . "\t\t" .
            "'" . strtolower( $name ) . "' => \\App\\Robots\\" . ucfirst( $name ) . "\\Robot::class,",
            $file );

        file_put_contents( app_path( 'Robots/Kernel.php' ), $file );
    }
}
