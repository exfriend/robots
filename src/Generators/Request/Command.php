<?php

namespace Exfriend\Robots\Generators\Request;

use Exfriend\Robots\Generators\BaseCommand;
use Exfriend\Robots\Generators\Request\Generator as RequestGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Command extends BaseCommand
{
    protected $name = 'robots:request';
    protected $namespace = 'App\\Robots';

    protected $description = 'Generate Request';
    /**
     * @var Generator
     */
    private $generator;

    public function __construct( RequestGenerator $generator )
    {
        $this->generator = $generator;
        parent::__construct();
    }

    public function fire()
    {
        $name = ucfirst( $this->askArgument( 'name', 'Please name your Request e.g. GetMainPageRequest', 'Request' ) );
        $url = $this->askOption( 'url', 'Enter optional url', 'http://' );
        if ( !$robot = $this->askOption( 'robot', 'Please enter the name of the robot', '' ) )
        {
            $this->error( 'Cannot proceed without knowing the robot name' );
        }


        $this->generator->generate( $name, $robot, [ 'class' => $name, 'url' => $url ] );

        $this->info( 'Request created successfully.' );
    }

    protected function getArguments()
    {
        return [
            [ 'name', InputArgument::REQUIRED, 'The name of the Request, e.g. GetProductPageRequest' ],
        ];
    }

    protected function getOptions()
    {
        return [
            [ 'robot', 'r', InputOption::VALUE_REQUIRED, 'Robot name, lowercase, e.g. ebay' ],
            [ 'url', 'u', InputOption::VALUE_REQUIRED, 'Request url, e.g. http://ebay.com/' ],
        ];
    }

}
