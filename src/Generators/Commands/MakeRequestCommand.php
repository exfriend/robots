<?php

namespace Exfriend\Robots\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeRequestCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:robots:request';
    protected $namespace = 'App\\Robots';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new outgoing request';

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
        $this->makeRequest();
    }

    /**
     * Generate the desired migration.
     */
    protected function makeRequest()
    {
        $name = $this->argument( 'name' );
        if ( $robot = $this->option( 'robot' ) )
        {
            $name = ucwords( $robot ) . '/Requests/' . $name;
        }

        if ( $this->files->exists( $path = $this->getPath( $name ) ) )
        {
            return $this->error( 'File ' . $name . ' already exists!' );
        }

        $this->makeDirectory( $path );

        $this->files->put( $path, $this->compileRequestStub() );

        $this->info( 'Request created successfully.' );

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
    protected function compileRequestStub()
    {
        $stub = $this->files->get( __DIR__ . '/../../stubs/request.stub' );

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

        $url = $this->option( 'url' );
        $stub = str_replace( '{{url}}', $url, $stub );

        return $this;
    }

    protected function replaceNamespace( &$stub )
    {
        $namespace = $this->namespace;
        if ( $name = $this->option( 'robot' ) )
        {
            $namespace .= '\\' . ucwords( $name ).'\\Requests';
        }

        $stub = str_replace( '{{namespace}}', $namespace, $stub );

        return $this;
    }

    protected function getArguments()
    {
        return [
            [ 'name', InputArgument::OPTIONAL, 'The name of the request', 'Request' ],
        ];
    }

    protected function getOptions()
    {
        return [
            [ 'robot', 'r', InputOption::VALUE_OPTIONAL, 'Robot name to add to the namespace?', null ],
            [ 'url', 'u', InputOption::VALUE_OPTIONAL, 'URL', null ],
        ];
    }
}
