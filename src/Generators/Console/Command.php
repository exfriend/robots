<?php

namespace Exfriend\Robots\Generators\Console;

use Exfriend\Robots\Generators\BaseCommand;
use Exfriend\Robots\Generators\Console\Generator as tGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Command extends BaseCommand
{
    protected $name = 'robots:console';
    protected $namespace = 'App\\Robots';

    protected $description = 'Generate Console Command';
    /**
     * @var Generator
     */
    private $generator;

    public function __construct( tGenerator $generator )
    {
        $this->generator = $generator;
        parent::__construct();
    }

    public function fire()
    {
        $name = ucfirst( $this->askArgument( 'name', 'Any non-default name?', '' ) );
        $title = $this->askOption( 'title', 'Enter Title', 'Scrape some website' );
        $description = $this->askOption( 'description', 'Enter Description', 'Command to handle the scrape' );

        if ( !$signature = $this->askOption( 'signature', 'Please enter the signature e.g. scrape:ebay', '' ) )
        {
            $this->error( 'Cannot proceed without knowing the signature' );
        }
        if ( !$robot = $this->askOption( 'robot', 'Please enter the name of the robot', '' ) )
        {
            $this->error( 'Cannot proceed without knowing the robot name' );
        }


        $this->generator->generate( $name, $robot, [ 'class' => $name,
            'signature' => $signature,
            'title' => $title,
            'description' => $description,
        ] );

        $this->robot = $robot;
        $this->cmd_name = $name;
        $this->info( 'Command created successfully.' );

        if ( $this->confirm( 'Register it in Console\Kernel?', 'yes' ) )
        {
            $this->addToConsoleKernel( $name );
        }
    }

    protected function getArguments()
    {
        return [
            [ 'name', InputArgument::OPTIONAL, 'The name of the Command', 'Command' ],
        ];
    }

    protected function getOptions()
    {
        return [
            [ 'robot', 'r', InputOption::VALUE_REQUIRED, 'Robot name, lowercase, e.g. ebay' ],
            [ 'signature', 's', InputOption::VALUE_REQUIRED, 'Command signature e.g. scrape:ebay' ],
            [ 'title', 't', InputOption::VALUE_REQUIRED, 'Command title' ],
            [ 'description', 'd', InputOption::VALUE_REQUIRED, 'Command description' ],
        ];
    }


    protected function addToConsoleKernel()
    {
        $classpath = '\\App\\Robots\\' . ucfirst( $this->robot ) . '\\' . $this->cmd_name . '::class,';
        $file = file_get_contents( app_path( 'Console/Kernel.php' ) );

        if ( str_contains( $file, $classpath ) )
        {
            return $this->line( 'Command already registered' );
        }

        $file = preg_replace(
            '~protected\s*?\$commands\s*?=\s*?\[~ims',
            'protected $commands= [' . PHP_EOL . "\t\t" . $classpath,
            $file
        );

        file_put_contents( app_path( 'Console/Kernel.php' ), $file );
    }
}
