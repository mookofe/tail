<?php namespace Foolkaka\Tail\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Tail Facade Class
 *
 * @author Victor Cruz <cruzrosario@gmail.com> 
 */
class Tail extends Facade {
    
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'Tail'; }

}