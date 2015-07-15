mookofe/tail
=========

RabbitMQ and PHP client for Laravel that allows you to add and listen queues messages just simple.

[![Build Status](https://travis-ci.org/mookofe/tail.svg?branch=master)](https://travis-ci.org/mookofe/tail)
[![Latest Stable Version](https://poser.pugx.org/mookofe/tail/v/stable.svg)](https://packagist.org/packages/mookofe/tail)
[![License](https://poser.pugx.org/mookofe/tail/license.svg)](https://packagist.org/packages/mookofe/tail)

Features
----
  - Simple queue configuration
  - Multiple server connections
  - Add message to queues easily
  - Listen queues with useful options


Requirements
----
  - videlalvaro/php-amqplib: 2.*


Version
----
1.0.3


Installation
--------------

**Preparation**

Open your composer.json file and add the following to the require array: 

```js
"mookofe/tail": "1.*"
```

**Install dependencies**

```
$ php composer install
```

Or

```batch
$ php composer update
```

Integration
--------------

After installing the package, open your Laravel config file config/app.php and add the following lines.

In the $providers array add the following service provider for this package.

```batch
'Mookofe\Tail\ServiceProvider',
```

In the $aliases array add the following facade for this package.

```batch
'Tail' => 'Mookofe\Tail\Facades\Tail',
```

RabbitMQ Connections
--------------
By default the library will use the RabbitMQ installation credentials (on a fresh installation the user "guest" is created with password "guest").

To override the default connection or add more servers run:

```batch
$ php artisan vendor:publish --provider="Mookofe\Tail\ServiceProvider" --tag="config"
```

Edit the RabbitMQ connections file at: **app/config/tail-settings.php**

```php
return array(

    'default' => 'default_connection',

    'connections' => array(

        'default_connection' => array(
            'host'         => 'localhost',
            'port'         => 5672,
            'username'     => 'guest',
            'password'     => 'guest',
            'vhost'        => '/',
            'exchange'     => 'default_exchange_name',
            'consumer_tag' => 'consumer',
        ),    
        'other_server' => array(
            'host'         => '192.168.0.10',
            'port'         => 5672,
            'username'     => 'guest',
            'password'     => 'guest',
            'vhost'        => '/',
            'exchange'     => 'default_exchange_name',
            'consumer_tag' => 'consumer',
        ),   
    ),
);
```



Adding messages to queue:
----

**Adding a simple message**

```php
    Tail::add('queue-name', 'message');
```

**Adding message changing RabbitMQ server**

```php	
    Tail::add('queue-name', 'message', array('connection_name' => 'connection_name_config_file'));
```


**Adding message with different exchange**

```php
    Tail::add('queue-name', 'message', array('exchange' => 'exchange_name'));
```

**Adding message with different options**

```php
	$options = array (
		'connection_name' => 'connection_name_config_file',
		'exchange' => 'exchange_name',
		'vhost' => 'vhost'
	);	
	
    Tail::add('queue-name', 'message', $options);
```


**Using Tail object**

```php
	$message = new Tail::createMessage;
	$message->queue_name = 'queue-name';
	$message->message = 'message';
	$message->connection_name = 'connection_name_in_config_file';
	$message->exchange = 'exchange_name';
	$message->vhost = 'vhost';

	$message->save();
```

Listening queues:
----

**Closure based listener**

```php
Tail::listen('queue-name', function ($message) {
    		
	//Your message logic code
});
```

**Closure listener with options**

```php
$options = array(
	'message_limit' => 50,
	'time' => 60,
	'empty_queue_timeout' => 5,
	'connection_name' => 'connection_name_in_config_file',
    'exchange' => 'exchange_name',
    'vhost' => 'vhost'
);

Tail::listenWithOptions('queue-name', $options, function ($message) {
    		
	//Your message logic code		
});
```

**Options definitions:**

|  Name | Description  | Default value|
|---|---|---|
| queue_name | Queue name on RabbitMQ  | * Required |
| message_limit | Number of messages to be proccessed   | 0: Unlimited |
| time | Time in seconds the proccess will be running   | 0: Unlimited |
| empty\_queue\_timeout | Time in seconds to kill listening when the queue is empty | 0: Unlimited |
| connection_name | Server connection name  | Defined at connections file  |
| exchange | Exchange name on RabbitMQ Server | Specified on connections file |
| vhost | Virtual host on RabbitMQ Server | Specified on connections file |


By default the listen proccess will be running forever unless you especify one of the running time arguments above (message\_limit, time, empty\_queue\_timeout). They can be mixed all together, so when one of the condition	is met the proccess will be stopped.



License
----
This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)