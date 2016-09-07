<?php

namespace Exfriend\Robots\Generators;

use Illuminate\Console\Command as LaravelCommand;

class BaseCommand extends LaravelCommand
{
    protected $namespace = 'App\\Robots';

    protected function askOption( $name, $question, $default )
    {
        if ( !$resp = !empty( $this->option( $name ) ) ? $this->option( $name ) : false )
        {
            return $this->ask( $question, $default );
        }
        return $resp;
    }

    protected function askArgument( $name, $question, $default )
    {
        if ( !$resp = !empty( $this->argument( $name ) ) ? $this->argument( $name ) : false )
        {
            return $this->ask( $question, $default );
        }
        return $resp;
    }

}
