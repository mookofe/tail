mookofe/tail
=========

RabbitMQ and PHP client that allows you to add and listen queues messages just simple.


Features
----
  - Simple queue configuration
  - Multiple server connections
  - Add message to queues easly
  - Listen queues with useful options


Requirements
----
  - videlalvaro/php-amqplib: 2.*


Version
----
1.0.0


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

Edit the RabbitMQ connections file at: **app/config/packages/mookofe/tail/config.php**

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
    Tail::add('queue-name', 'message', array('connection' => 'connection_name'));
```


**Adding message with different exchange**

```php
    Tail::add('queue-name', 'message', array('exchange' => 'exchange_name'));
```

**Adding message with different options**

```php
	$options = array (
		'connection' => 'connection_name',
		'exchange' => 'exchange_name',
		'vhost' => 'vhost'
	);	
	
    Tail::add('queue-name', 'message', $options);
```


**Using Tail object**

```php
	$message = new Tail::createMessage;
	$message->queueName = 'queue-name';
	$message->message = 'message';
	$message->connection = 'connection_name';
	$message->exchange = 'exchange_name';
	$message->vhost = 'vhost';

	$message->save();
```

Listening queues:
----

**Callback based listener**

```php
Tail::listen('queue-name', function ($message) {
    		
	//Your message logic code
});
```

**Callback listener with options**

```php
$options = array(
	'message-limit' => 50,
	'time' => 60,
	'empty-queue-timeout' => 5,
	'connection' => 'connection_name',
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
| --queuename | Queue name on RabbitMQ  | * Required |
| --message-limit | Number of messages to be proccessed   | 0: Unlimited |
| --time | Time in seconds the proccess will be running   | 0: Unlimited |
| --empty-queue-timeout | Time in seconds to kill listening when the queue is empty | 0: Unlimited |
| --connection-name | Server connection name  | Defined at connections file  |
| --exchange-name | Exchange name on RabbitMQ Server | Specified on connections file |
| --vhost | Virtual host on RabbitMQ Server | Specified on connections file |


By default the listen proccess will be running forever unless you especify one of the running-time arguments above (message-limit, time, empty-queue-timeout). They can be mixed all together, so when one of the condition	is met the proccess will be stopped.



License
----
This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)