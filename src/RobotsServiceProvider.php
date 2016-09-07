<?php

namespace Exfriend\Robots;

use App\Robots\Kernel as RoboKernel;
use Illuminate\Support\ServiceProvider;

class RobotsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton( 'command.robots.make.request', function ( $app )
        {
            return $app[ \Exfriend\Robots\Generators\Request\Command::class ];
        } );
        $this->app->singleton( 'command.robots.make.robot', function ( $app )
        {
            return $app[ \Exfriend\Robots\Generators\Robot\Command::class ];
        } );
        $this->app->singleton( 'command.robots.make.console', function ( $app )
        {
            return $app[ \Exfriend\Robots\Generators\Console\Command::class ];
        } );
        $this->app->singleton( 'command.robots.make.dto', function ( $app )
        {
            return $app[ \Exfriend\Robots\Generators\ExtractedItem\Command::class ];
        } );
        $this->app->singleton( 'command.robots.make.parser', function ( $app )
        {
            return $app[ \Exfriend\Robots\Generators\Parser\Command::class ];
        } );
        //        $this->app->singleton( 'command.robots.make.scraper', function ( $app )
        //        {
        //            return $app[ 'Exfriend\Robots\Commands\MakeScraperCommand' ];
        //        } );


        $roboKernel = new RoboKernel();

        foreach ( $roboKernel->getRobots() as $robot => $handler )
        {

            $this->app->singleton( 'robots.' . $robot, function () use ( $handler )
            {
                $xtract = new $handler();
                return $xtract;
            } );
        }

        require( __DIR__ . '/helpers.php' );

        $this->commands( 'command.robots.make.request' );
        $this->commands( 'command.robots.make.robot' );
        $this->commands( 'command.robots.make.console' );
        $this->commands( 'command.robots.make.dto' );
        $this->commands( 'command.robots.make.parser' );
        //        $this->commands( 'command.robots.make.scraper' );
    }
}
