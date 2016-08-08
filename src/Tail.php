<?php namespace Mookofe\Tail;

use App;
use Closure;
use Mookofe\Tail\Message;
use Mookofe\Tail\Listener;

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
        $msg = App::make('Mookofe\Tail\Message');
        $msg->add($queueName, $message, $options);
    }

    /**
     * Create new blank message instance
     *
     * @return Mookofe\Tail\Message
     */
    public function createMessage()
    {
        return App::make('Mookofe\Tail\Message');
    }

    /**
     * Listen queue server for given queue name
     *
     * @param string $queue_name  Queue name to listen
     *
     * @return void
     */
    public function listen($queue_name, Closure $callback)
    {
        $listener = App::make('Mookofe\Tail\Listener');
        $listener->listen($queue_name, null, null, null, $callback);
    }

    /**
     * Listen queue server for given queue name
     *
     * @param string $queue_name  Queue name to listen
     * @param int $qos_prefetch_size  QoS pre-fetch size
     * @param int $qos_prefetch_count  QoS pre-fetch count
     * @param Closure $closure Function to run for every message
     *
     * @return void
     */
    public function listenWithQoS($queue_name, $qos_prefetch_size = null, $qos_prefetch_count = null, Closure $callback)
    {
        $listener = App::make('Mookofe\Tail\Listener');
        $listener->listen($queue_name, null, $qos_prefetch_size, $qos_prefetch_count, $callback);
    }

    /**
     * Listen queue server for given queue name
     *
     * @param string $queue_name  Queue name to listen
     * @param array $options  Options to listen
     * @param int $qos_prefetch_size  QoS pre-fetch size
     * @param int $qos_prefetch_count  QoS pre-fetch count
     * @param Closure $closure Function to run for every message
     *
     * @return void
     */
    public function listenWithOptions($queue_name, array $options, $qos_prefetch_size = null, $qos_prefetch_count = null, Closure $callback)
    {
        $listener = App::make('Mookofe\Tail\Listener');
        $listener->listen($queue_name, $options, $qos_prefetch_size, $qos_prefetch_count, $callback);
    }

}
