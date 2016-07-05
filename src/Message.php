<?php namespace Mookofe\Tail;

use Config;
use Mookofe\Tail\Connection;
use Mookofe\Tail\BaseOptions;
use Illuminate\Config\Repository;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPConnection;

/**
 * Message class, used to manage messages back and forth with the server
 *
 * @author Victor Cruz <cruzrosario@gmail.com>
 * @author Martin Hilscher <hilscher@jungehaie.com>
 */
class Message extends BaseOptions {


    /**
     * Message to be send or received from the queue server
     *
     * @var string
     */
    public $message;

    /**
     * Message constructor
     *
     * @param array $options  Options array to get validated
     *
     * @return Mookofe\Tail\Message
     */
    public function __construct(Repository $config, array $options = NULL)
    {
        parent::__construct($config);

        if ($options)
            $this->setOptions($options);
    }

    /**
     * Add a message directly to the queue server
     *
     * @param string $queue_name  Queue name on RabbitMQ
     * @param string $message  Message to be add to the queue server
     * @param array $options  Options values for message to add
     *
     * @return void
     */
    public function add($queue_name, $message, array $options = NULL)
    {
        $this->queue_name = $queue_name;
        $this->message = $message;

        if ($options)
            $this->setOptions($options);

        $this->save();
    }

    /**
     * Save the current message instance into de queue server
     *
     * @return void
     */
    public function save()
    {
        try
        {
            $connection = new Connection($this->buildConnectionOptions());
            $connection->open();

            $msg = new AMQPMessage($this->message, array('content_type' => $this->content_type, 'delivery_mode' => 2));
            $connection->channel->basic_publish($msg, $this->exchange, $this->queue_name);

            $connection->close();
        }
        catch (Exception $e)
        {
            $connection->close();
            throw new Exception($e);
        }
    }

}