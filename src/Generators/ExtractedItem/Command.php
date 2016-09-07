<?php

namespace Exfriend\Robots\Generators\ExtractedItem;

use Exfriend\Robots\Generators\BaseCommand;
use Exfriend\Robots\Generators\ExtractedItem\Generator as DtoGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Command extends BaseCommand
{
    protected $name = 'robots:data';
    protected $namespace = 'App\\Robots';

    protected $description = 'Define Data Object';
    /**
     * @var Generator
     */
    private $generator;

    public function __construct( DtoGenerator $generator )
    {
        $this->generator = $generator;
        parent::__construct();
    }

    public function fire()
    {
        $name = ucfirst( $this->askArgument( 'name', 'Please name your Data Object', 'Product' ) );
        if ( !$robot = $this->askOption( 'robot', 'Please enter the name of the robot', '' ) )
        {
            $this->error( 'Cannot proceed without knowing the robot name' );
        }


        $this->generator->generate( $name, $robot, [ 'class' => $name, ] );

        $this->info( 'Data Object created successfully.' );
    }

    protected function getArguments()
    {
        return [
            [ 'name', InputArgument::REQUIRED, 'The name of the DTO, e.g. Product' ],
        ];
    }

    protected function getOptions()
    {
        return [
            [ 'robot', 'r', InputOption::VALUE_REQUIRED, 'Robot name, lowercase, e.g. ebay' ],
        ];
    }

}
