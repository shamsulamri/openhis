<?php

namespace App;

use Amqp;
use Log;
use Config;

class AMQPHelper 
{
	public static function pushMessage($routing_key, $message)
	{
			Amqp::publish($routing_key, $message, [	
						'host'=>env('RABBITMQ_HOST', 'localhost'),
						'port'=>env('RABBITMQ_PORT', 5672),
						'username'=>env('RABBITMQ_LOGIN', 'guest'),
						'password'=>env('RABBITMQ_PASSWORD', 'guest'),
						'vhost'=>env('RABBITMQ_VHOST', '/'),
						'exchange'=>env('RABBITMQ_EXCHANGE', ''),
						'exchange_type'=>'direct',
						'exchange_durable'=>true,
						]);
	}

}
