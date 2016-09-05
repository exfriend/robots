<?php


namespace Exfriend\Robots\Generators;

use Illuminate\Filesystem\Filesystem;


abstract class Generator
{

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Composer
     */
    protected $composer;

    public function __construct( Filesystem $files )
    {
        $this->files = $files;
        $this->composer = app()[ 'composer' ];
    }

    abstract protected function getOutputFilePath();

    abstract protected function getNamespace();

    protected function handle( $stubName, $bindings )
    {
        $bindings[ 'namespace' ] = $this->getNamespace();
        $outputFilePath = $this->getOutputFilePath();
        if ( $this->files->exists( $outputFilePath ) )
        {
            throw new \Exception( 'File ' . $outputFilePath . ' already exists!' );
        }
        $this->makeDirectory( $outputFilePath );

        $compiler = new StubCompiler( $stubName );
        $file = $compiler->compile( $bindings );

        $this->files->put( $outputFilePath, $file );
        $this->composer->dumpAutoloads();
    }

    protected function makeDirectory( $path )
    {
        if ( !$this->files->isDirectory( dirname( $path ) ) )
        {
            $this->files->makeDirectory( dirname( $path ), 0777, true, true );
        }
    }


}