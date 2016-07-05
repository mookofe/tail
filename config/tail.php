<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Default AMQP Server Connection
    |--------------------------------------------------------------------------
    |
    | The name of your default AMQP server connection. This connection will 
    | be used as the default for all queues operations unless a different 
    | name is given when performing said operation. This connection name
    | should be listed in the array of connections below.
    |
    */
    'default' => 'default_connection',

    /*
    |--------------------------------------------------------------------------
    | Queues Connections
    |--------------------------------------------------------------------------
    */

    'connections' => array(

        'default_connection' => array(
            'host'          => 'localhost',
            'port'          => 5672,
            'username'      => 'guest',
            'password'      => 'guest',
            'vhost'         => '/',
            'exchange'      => 'amq.direct',
            'consumer_tag'  => 'consumer',
            'exchange_type' => 'direct',
            'content_type'  => 'text/plain'
        ),
    ),
);