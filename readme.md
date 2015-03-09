mookofe/tail
=========

RabbitMQ and PHP client that allows you to add and listen queues messages just simple.


Features
----
  - Simple queue configuration
  - Multiple server connections
  - Add queues message easly
  - Listen queues with useful options
  - Command line queue messages options


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
php composer install
```

Or

```batch
php composer update
```

Integration
--------------

After installing the package, open your Laravel config file app/config/app.php and add the following lines.

In the $providers array add the following service provider for this package.

```batch
'Mookofe\Tail\TailServiceProvider',
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
php artisan config:publish --package=mookofe/tail
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
    Tail::add('queue-name', 'message', 'connection_name');
```


**Adding message with different exchange**

```php
    Tail::add('queue-name', 'message', false, 'exchange_name');
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

**Simple listener**

```php
    Tail::listen('queue-name', function ($message) {
    		
    	//Your message logic    	
    });
```

###Listen using command line
You might want to use your queue message logic in your command line in order to be able to run it using crons, other scripts, etc.

To do so, you will create a class for each diferent queue on RabbitMQ using the following structure.

```php
<?php

use Mookofe\Tail\AbstractListener;

class myQueueNameListener extends AbstractListener {

    /*
     * Queue name on RabbitMQ
     */        
    protected $queueName = 'queue-name';
    

    /*
     * Method called for each message on queue
     */
    public function listen()
    {
        $message = $this->getMessage();
        
        //Do some logic with message
    }
}
```

Internally, the library find the class matching with this queue name and run the listen method. You just have to use the following artisan command to listen your queue:

```batch
php artisan tail:listen --queue-name=queue_name
```

**Command arguments:**

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