<?php

namespace Exfriend\Robots\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

class MakeConsoleCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:robots:console';
    protected $namespace = 'App\\Robots';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new console command';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     */
    public function __construct( Filesystem $files )
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = app()[ 'composer' ];
    }

    public function fire()
    {
        $this->makeConsole();
    }

    protected function makeConsole()
    {
        $name = ucfirst( $this->argument( 'name' ) );

        if ( $this->files->exists( $path = $this->getPath( $name ) ) )
        {
            return $this->error( 'File ' . $name . ' already exists!' );
        }

        $this->makeDirectory( $path );

        $this->files->put( $path, $this->compileStub() );

        $this->info( 'Command created successfully.' );

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
        return app_path() . '/Robots/' . $name . '/Command.php';
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
        $stub = $this->files->get( __DIR__ . '/../../stubs/console.stub' );

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
        $className = 'Command';

        $stub = str_replace( '{{class}}', $className, $stub );

        return $this;
    }


    public function addToKernel( $name )
    {
        $file = file_get_contents( app_path( 'Console/Kernel.php' ) );
        $file = str_replace( 'protected $commands = [',
            'protected $commands = [' . PHP_EOL . "\t\t" .
            "'" . strtolower( $name ) . "' => \\App\\Robots\\" . ucfirst( $name ) . "\\Command::class,",
            $file );

        file_put_contents( app_path( 'Console/Kernel.php' ), $file );
    }

    protected function replaceNamespace( &$stub )
    {
        $namespace = $this->namespace;
        $namespace .= '\\' . ucwords( $this->argument( 'name' ) );

        $stub = str_replace( '{{namespace}}', $namespace, $stub );
        $stub = str_replace( '{{signature}}', $this->argument( 'signature' ), $stub );
        $stub = str_replace( '{{title}}', $this->argument( 'title' ), $stub );
        $stub = str_replace( '{{description}}', $this->argument( 'description' ), $stub );

        return $this;
    }

    protected function getArguments()
    {
        return [
            [ 'name', InputArgument::REQUIRED, 'The name of the robot' ],
            [ 'signature', InputArgument::REQUIRED, 'e.g. scrape:ebay' ],
            [ 'title', InputArgument::REQUIRED, 'e.g. eBay Scraper' ],
            [ 'description', InputArgument::REQUIRED, 'Nice description for UI' ],
        ];
    }

}
