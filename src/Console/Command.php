<?php
namespace Exfriend\Robots\Console;

class Command extends \Exfriend\Overseer\Command
{

    public function say( $text )
    {
        return $this->line( $text );
    }
}