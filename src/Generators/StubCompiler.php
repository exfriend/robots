<?php


namespace Exfriend\Robots\Generators;


class StubCompiler
{
    protected $stub;
    protected $stubPath = __DIR__ . '/../../stubs/';

    public function __construct( $stub )
    {
        $this->stub = $this->normalizeStub( $stub );
    }

    public function compile( $replacements )
    {
        $stub = $this->stub;
        foreach ( $replacements as $key => $value )
        {
            $stub = str_replace( '{{' . $key . '}}', $value, $stub );
        }

        return $stub;
    }

    protected function normalizeStub( $stub )
    {
        if ( file_exists( $this->stubPath . $stub . '.stub' ) )
        {
            return file_get_contents( $this->stubPath . $stub . '.stub' );
        }

        return $stub;
    }

}