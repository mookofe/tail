<?php namespace Mookofe\Tail;

use Config;
use Illuminate\Config\Repository;
use Mookofe\Tail\Exceptions\InvalidOptionException;

/**
 * Base class options used to wrap common methods for connection, listening and adding messages
 *
 * @author Victor Cruz <cruzrosario@gmail.com>
 */
class BaseOptions {

    /**
     * Valid options array, include all valid options can be set
     *
     * @var array
     */
    protected $allowedOptions = array('exchange', 'exchange_type', 'vhost', 'connection_name', 'queue_name', 'content_type');

    /**
     * Config repository dependency
     *
     * @var Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Exchange name on RabbitMQ Server
     *
     * @var string
     */
    public $exchange;

    /**
     * Virtual host name on RabbitMQ Server
     *
     * @var string
     */
    public $vhost;

    /**
     * Connection name defined in config/tail-settings.php file
     *
     * @var string
     */
    public $connection_name;

    /**
     * Queue name for this connection
     *
     * @var string
     */
    public $queue_name;

    /**
     * RabbitMQ AMQP exchange type
     * should be one of:
     *      direct, fanout, topic or headers
     *
     * @var string
     */
    public $exchange_type;

    /**
     * Content-Type for the messages send over this connection
     *
     * @var string
     */
    public $content_type;

    /**
     * Constructor
     *
     * @param Illuminate\Config\Repository $config  Config dependency
     *
     * @return Mookofe\Tail\BaseOptions
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Validate the given options with the allowed options
     *
     * @param array $options  Options array to get validated
     *
     * @return Mookofe\Tail\BaseOptions
     */
    public function validateOptions(array $options)
    {
        foreach ($options as $option => $value)
        {
            if (!in_array($option, $this->allowedOptions))
                throw new InvalidOptionException("Option [$option] is not valid");
        }

        return $this;
    }

    /**
     * Set the following options in the class
     *
     * @param array $options  Options with values to be set
     *
     * @return Mookofe\Tail\BaseOptions
     */
    public function setOptions(array $options)
    {
        //Validate options
        $this->validateOptions($options);

        //Set options
        foreach ($options as $option => $value)
            $this->$option = $value;

        return $this;
    }

    /**
     * Build options set to build a connection to the queue server
     *
     * @return array
     */
    public function buildConnectionOptions()
    {
        //Default connection
        $connection_name = $this->config->get("tail-settings.default");

        //Check if set for this connection
        if ($this->connection_name)
            $connection_name = $this->connection_name;

        $connectionOptions = $this->config->get("tail-settings.connections.$connection_name");

        //Adding default values to exchange_type and content_type to avoid breaking change
        if (!isset($connectionOptions['exchange_type']))
            $connectionOptions['exchange_type'] = 'direct';
        if (!isset($connectionOptions['content_type']))
            $connectionOptions['content_type'] = 'text/plain';

        //Set current instance properties values
        if ($this->vhost)
            $connectionOptions['vhost'] = $this->vhost;
        if ($this->exchange)
            $connectionOptions['exchange'] = $this->exchange;
        if ($this->exchange_type)
            $connectionOptions['exchange_type'] = $this->exchange_type;
        if ($this->content_type)
            $connectionOptions['content_type'] = $this->content_type;

        //Queue specific options
        $connectionOptions['queue_name'] = $this->queue_name;

        return $connectionOptions;
    }
}