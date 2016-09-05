<?php

namespace Exfriend\Robots\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeDtoCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:robots:dto';
    protected $namespace = 'App\\Robots';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create extracted data transfer object';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Meta information for the requested migration.
     *
     * @var array
     */
    protected $meta;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * @param Composer $composer
     */
    public function __construct( Filesystem $files )
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = app()[ 'composer' ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->makeDto();
    }

    protected function makeDto()
    {
        $name = $this->argument( 'name' );
        if ( $robot = $this->option( 'robot' ) )
        {
            $name = ucwords( $robot ) . '/' . $name;
        }

        if ( $this->files->exists( $path = $this->getPath( $name ) ) )
        {
            return $this->error( 'File ' . $name . ' already exists!' );
        }

        $this->makeDirectory( $path );

        $this->files->put( $path, $this->compileStub() );

        $this->info( 'DTO created successfully.' );

        $this->composer->dumpAutoloads();
    }

    /**
     * Get the path to where we should store the migration.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath( $name )
    {
        return app_path() . '/Robots/' . $name . '.php';
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string $path
     * @return string
     */
    protected function makeDirectory( $path )
    {
        if ( !$this->files->isDirectory( dirname( $path ) ) )
        {
            $this->files->makeDirectory( dirname( $path ), 0777, true, true );
        }
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileStub()
    {
        $stub = $this->files->get( __DIR__ . '/../../stubs/dto.stub' );

        $this->replaceClassName( $stub );
        $this->replaceNamespace( $stub );

        return $stub;
    }

    /**
     * Replace the class name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceClassName( &$stub )
    {
        $className = ucwords( camel_case( $this->argument( 'name' ) ) );

        $stub = str_replace( '{{class}}', $className, $stub );

        return $this;
    }

    protected function replaceNamespace( &$stub )
    {
        $namespace = $this->namespace;
        if ( $name = $this->option( 'robot' ) )
        {
            $namespace .= '\\' . ucwords( $name );
        }

        $stub = str_replace( '{{namespace}}', $namespace, $stub );

        return $this;
    }

    protected function getArguments()
    {
        return [
            [ 'name', InputArgument::OPTIONAL, 'The name of the DTO', 'ExtractedItem' ],
        ];
    }

    protected function getOptions()
    {
        return [
            [ 'robot', 's', InputOption::VALUE_OPTIONAL, 'Robot name to add to the namespace?', null ],
        ];
    }
}
