<?php
namespace Exfriend\Robots\Generators\Console;

use Exfriend\Robots\Generators\Generator as BaseGenerator;

class Generator extends BaseGenerator
{
    protected $name;

    public function getNamespace()
    {
        return 'App\\Robots\\' . $this->robot;
    }

    public function getOutputFilePath()
    {
        return app_path() . '/Robots/' . ucfirst( $this->robot ) . '/' . $this->name . '.php';
    }


    public function generate( $name, $robot, $bindings )
    {
        $this->name = ucfirst( $name );
        $this->robot = ucfirst( $robot );
        $this->handle( 'console', $bindings );
    }

}