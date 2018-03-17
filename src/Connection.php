<?php namespace Mookofe\Tail;

use Exception;
use Mookofe\Tail\BaseOptions;
use PhpAmqpLib\Connection\AMQPSSLConnection;

/**
 * Connection class, used to manage connection to the RabbitMQ Server
 *
 * @author Victor Cruz <cruzrosario@gmail.com>
 */
class Connection extends BaseOptions{

    /**
     * RabbitMQ server name or IP
     *
     * @var string
     */
    public $host;

    /**
     * RabbitMQ server port
     *
     * @var string
     */
    public $port;

    /**
     * RabbitMQ server username
     *
     * @var string
     */
    public $username;

    /**
     * RabbitMQ server password
     *
     * @var string
     */
    public $password;

    /**
     * RabbitMQ server consumer tag
     *
     * @var string
     */
    public $consumer_tag;

    /**
     * RabbitMQ connection SSL context options
     *
     * @var array
     */
    public $ssl_context_options;

    /**
     * RabbitMQ connection timeout in seconds
     *
     * @var float
     */
    public $connection_timeout;

    /**
     * RabbitMQ connection read/write timeout in seconds
     *
     * @var float
     */
    public $read_write_timeout;

    /**
     * RabbitMQ connection keepalive flag
     *
     * @var bool
     */
    public $keepalive;

    /**
     * RabbitMQ connection heartbeat in seconds
     *
     * @var int
     */
    public $heartbeat;

    /**
     * RabbitMQ AMQP Connection
     *
     * @var PhpAmqpLib\Connection\AMQPConnection
     */
    public $AMQPConnection;

    /**
     * RabbitMQ AMQP channel
     *
     * @var PhpAmqpLib\Connection\AMQPConnection
     */
    public $channel;

    /**
     * Connection constructor
     *
     * @param array $options  Options array to set connection
     *
     * @return Mookofe\Tail\Connection
     */
    public function __construct(array $options = null)
    {
        $this->allowedOptions = array_merge($this->allowedOptions, array(
                'host',
                'port',
                'username',
                'password',
                'consumer_tag',
                'ssl_context_options',
                'connection_timeout',
                'read_write_timeout',
                'keepalive',
                'heartbeat'
            )
        );

        if (!$options)
            $options = $this->buildConnectionOptions();

        $this->setOptions($options);
    }

    /**
     * Open a connection with the RabbitMQ Server
     *
     * @return void
     */
    public function open()
    {
        try
        {
            $additionalConnectionOptions = array();
            foreach (array('connection_timeout', 'read_write_timeout', 'keepalive', 'heartbeat') as $option) {
                if (isset($this->$option)) {
                    $additionalConnectionOptions[$option] = $this->$option;
                }
            }
            $this->AMQPConnection = new AMQPSSLConnection(
                $this->host,
                $this->port,
                $this->username,
                $this->password,
                $this->vhost,
                $this->ssl_context_options,
                $additionalConnectionOptions
            );
            $this->channel = $this->AMQPConnection->channel();
            $this->channel->queue_declare($this->queue_name, false, false, false, false);
            $this->channel->exchange_declare($this->exchange, $this->exchange_type, false, true, false);
            $this->channel->queue_bind($this->queue_name, $this->exchange);
        }
        catch (Exception $e)
        {
            throw new Exception($e);
        }
    }

    /**
     * Close the connection with the RabbitMQ server
     *
     * @return void
     */
    public function close()
    {
        if (isset($this->channel))
            $this->channel->close();
        if (isset($this->AMQPConnection))
            $this->AMQPConnection->close();
    }
}
