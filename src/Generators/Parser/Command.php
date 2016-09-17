<?php

namespace Exfriend\Robots\Generators\Parser;

use Exfriend\Robots\Generators\BaseCommand;
use Exfriend\Robots\Generators\Parser\Generator as ParserGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Command extends BaseCommand
{
    protected $name = 'robots:parser';
    protected $namespace = 'App\\Robots';

    protected $description = 'Generate Parser class';
    /**
     * @var Generator
     */
    private $generator;

    public function __construct( ParserGenerator $generator )
    {
        $this->generator = $generator;
        parent::__construct();
    }

    public function fire()
    {
        $name = ucfirst( $this->askArgument( 'name', 'Any particular name for your Parser?', 'Parser' ) );

        if ( !$robot = $this->askOption( 'robot', 'Please enter the name of the robot', '' ) )
        {
            $this->error( 'Cannot proceed without knowing the robot name' );
        }


        $this->generator->generate( $name, $robot, [ 'class' => $name ] );

        $this->info( 'Parser created successfully.' );
    }

    protected function getArguments()
    {
        return [
            [ 'name', InputArgument::OPTIONAL, 'The name of the Parser, e.g. ProductPageParser', 'Parser' ],
        ];
    }

    protected function getOptions()
    {
        return [
            [ 'robot', 'r', InputOption::VALUE_REQUIRED, 'Robot name, lowercase, e.g. ebay' ],
        ];
    }

}
