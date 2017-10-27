<?php namespace Foolkaka\Tail;

use Exception;
use Foolkaka\Tail\BaseOptions;

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
     * RabbitMQ server ssl connect
     *
     * @var boolean
     */
    public $ssl_connect;

    /**
     * RabbitMQ cacert
     *
     * @var string
     */
    public $rabbitmq_cacert_pem;

    /**
     * RabbitMQ loacl cert
     *
     * @var string
     */
    public $rabbitmq_local_cert_pem;

    /**
     * Connection constructor
     *
     * @param array $options  Options array to set connection
     *
     * @return Mookofe\Tail\Connection
     */
    public function __construct(array $options = null)
    {
        $this->allowedOptions = array_merge($this->allowedOptions, array('host', 'port', 'username', 'password', 'consumer_tag'));

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
            if ($this->ssl_connect) {
                $sslOptions = array(
                    'cafile' => $this->rabbitmq_cacert_pem,
                    'local_cert' => $this->rabbitmq_local_cert_pem,
                    'verify_peer' => true
                );
                $this->AMQPConnection = new PhpAmqpLib\Connection\AMQPSSLConnection($this->host, $this->port, $this->username, $this->password, $this->vhost, $sslOptions);
            } else {
                $this->AMQPConnection = new PhpAmqpLib\Connection\AMQPConnection($this->host, $this->port, $this->username, $this->password, $this->vhost);
            }
            $this->channel = $this->AMQPConnection->channel();
            $this->channel->queue_declare($this->queue_name, false, true, false, false);
            $this->channel->exchange_declare($this->exchange, $this->exchange_type, false, true, false);
            $this->channel->queue_bind($this->queue_name, $this->exchange);
        } catch (Exception $e) {
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
        if (isset($this->AMQPConnection))
            $this->AMQPConnection->close();
        if (isset($this->channel))
            $this->channel->close();
    }
}