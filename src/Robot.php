<?php

namespace Exfriend\Robots;

use Illuminate\Console\Command;

abstract class Robot
{
    public $cookie_file;
    public $base_url;

    /**
     * @var \Exfriend\Robots\Console\Command
     */
    protected $command;

    public function __construct()
    {

        $this->engine = ( new Engine() )->setThreads( 100 );
        $this->configure();
    }

    abstract public function configure();

    abstract public function handle();

    public function setProgress( $progress )
    {
        if ( $this->command )
        {
            $this->command->setProgress( $progress );
        }
        return true;
    }


    public function say( $text )
    {
        return $this->line( $text );
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function setCommand( Command $command )
    {
        $this->command = $command;
        return $this;
    }

    protected function getRobotName()
    {
        $classpath = explode( '\\', get_class( $this ) );
        return $classpath[ 1 ];
    }

    public function __call( $name, $args )
    {
        return call_user_func_array( [ $this->command, $name ], $args );
    }

    public function wait()
    {
        sleep( rand( 3, 5 ) );
    }
}
