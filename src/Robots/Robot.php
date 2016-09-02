<?php

namespace Exfriend\Robots;

use Illuminate\Console\Command;

class Robot
{

    public $url;
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

    public function configure()
    {
    }

    public function handle()
    {
    }

    public function get( $url )
    {
        return $this->engine->run( new Request( $url ) );
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
        return call_user_func_array( $this->command, $args );
    }
}
