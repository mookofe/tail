<?php namespace Foolkaka\Tail;

use App;
use Closure;

/**
 * Tail class, used as facade handler
 *
 * @author Victor Cruz <cruzrosario@gmail.com> 
 */
class Tail {

    /**
     * Add a message directly to the queue server
     *
     * @param string $queue_name  Queue name on RabbitMQ
     * @param string $message  Message to be add to the queue server
     * @param array $options  Options values for message to add
     *
     * @return void
     */
    public function add($queueName, $message, array $options = null)
    {
        $msg = App::make(Message::class);
        $msg->add($queueName, $message, $options);
    }

    /**
     * Create new blank message instance
     *
     * @return Foolkaka\Tail\Message
     */
    public function createMessage()
    {
        return App::make(Message::class);
    }

    /**
     * Listen queue server for given queue name
     *
     * @param string $queue_name  Queue name to listen
     * @param array $options  Options to listen
     *
     * @return void
     */
    public function listen($queue_name, Closure $callback)
    {
        $listener = App::make(Listener::class);
        $listener->listen($queue_name, null, $callback);
    }

    /**
     * Listen queue server for given queue name
     *
     * @param string $queue_name  Queue name to listen
     * @param array $options  Options to listen
     * @param Closure $closure Function to run for every message
     *
     * @return void
     */
    public function listenWithOptions($queue_name, array $options, Closure $callback)
    {
        $listener = App::make(Listener::class);
        $listener->listen($queue_name, $options, $callback);
    }
    
}