foolkaka/tail
=========

RabbitMQ and PHP client for Laravel and Lumen that allows you to add and listen queues messages just simple.

[![Build Status](https://travis-ci.org/foolkaka/tail.svg?branch=master)](https://travis-ci.org/foolkaka/tail)
[![Latest Stable Version](https://poser.pugx.org/foolkaka/tail/v/stable.svg)](https://packagist.org/packages/foolkaka/tail)
[![License](https://poser.pugx.org/foolkaka/tail/license.svg)](https://packagist.org/packages/foolkaka/tail)

Features
----
  - Simple queue configuration
  - Multiple server connections
  - Add message to queues easily
  - Listen queues with useful options


Requirements
----
  - php-amqplib/php-amqplib: 2.*


Version
----
1.0.6


Installation
--------------

**Preparation**

Open your composer.json file and add the following to the require array: 

```js
"foolkaka/tail": "1.*"
```

**Install dependencies**

```
$ composer install
```

Or

```batch
$ composer update
```

Integration
--------------
### Laravel
After installing the package, open your Laravel config file **config/app.php** and add the following lines.

In the $providers array add the following service provider for this package.

```batch
Foolkaka\Tail\ServiceProvider::class,
```

In the $aliases array add the following facade for this package.

```batch
'Tail' => Foolkaka\Tail\Facades\Tail::class,
```

Add servers connection file running:

```batch
$ php artisan vendor:publish --provider="Foolkaka\Tail\ServiceProvider" --tag="config"
```

### Lumen
Register the Lumen Service Provider in **bootstrap/app.php**:

```php
/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
*/

//...

$app->configure('tail-settings');
$app->register(Foolkaka\Tail\LumenServiceProvider::class);

//...
```

Make sure sure `$app->withFacades();` is uncomment in your **bootstrap/app.php** file


Create a **config** folder in the root directory of your Lumen application and copy the content
from **vendor/foolkaka/tail/config/tail.php** to **config/tail-settings.php**.

RabbitMQ Connections
--------------
By default the library will use the RabbitMQ installation credentials (on a fresh installation the user "guest" is created with password "guest").

To override the default connection or add more servers, edit the RabbitMQ connections file at: **config/tail-settings.php**

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
            'exchange_type'=> 'direct',
            'content_type' => 'text/plain',
            'ssl_connect'   => false,
            'rabbitmq_cacert_pem'   => '',
            'rabbitmq_loacl_cert_pem' => ''
        ),    
        'other_server' => array(
            'host'         => '192.168.0.10',
            'port'         => 5672,
            'username'     => 'guest',
            'password'     => 'guest',
            'vhost'        => '/',
            'exchange'     => 'default_exchange_name',
            'consumer_tag' => 'consumer',
            'exchange_type'=> 'fanout',
            'content_type' => 'application/json',
            'ssl_connect'   => false,
            'rabbitmq_cacert_pem'   => '',
            'rabbitmq_loacl_cert_pem' => ''
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

**Adding message with different content type**

```php
    Tail::add('queue-name', '{ 'message' : 'message' }', array('content_type' => 'application/json'));
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
	$message->content_type = 'content/type'

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
| message_limit | Number of messages to be processed   | 0: Unlimited |
| time | Time in seconds the process will be running   | 0: Unlimited |
| empty\_queue\_timeout | Time in seconds to kill listening when the queue is empty | 0: Unlimited |
| connection_name | Server connection name  | Defined at connections file  |
| exchange | Exchange name on RabbitMQ Server | Specified on connections file |
| vhost | Virtual host on RabbitMQ Server | Specified on connections file |


By default the listen process will be running forever unless you specify one of the running time arguments above (message\_limit, time, empty\_queue\_timeout). They can be mixed all together, so when one of the condition	is met the process will be stopped.



License
----
This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)