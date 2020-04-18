# Running the application Locally

## dependencies:
To start application you need to install a couple of dependencies:

1. Docker for MacOS https://docs.docker.com/docker-for-mac/install/

## Using the environment

```
docker-compose up -d
```

Running without -d will keep docker in foreground.

You will then be able to access the application by pointing your browser
to http://localhost:91.

*Bare in mind that docker.php.dist configuration has job server set up to `beanstalkd-lb`. You'll need to set this value to 0.0.0.0 if you do not need a job server*

To run cli scripts you may want to SSH to the container with:

```
docker-compose exec -it [container-name] bash
```

Where [container-name] is one of containers defined in docker-compose.yaml
Eg. php, worker, db etc...

Turning off the virtual machine with:

```
docker-compose down
```

## Installation

Regardless of the environment setup method, there are some common steps to be performed:
    
1. `$ composer install`
1. `$ cp config/*.conf.php.dist config/*.conf.php`
    1. Create MySQL database, for example `billing_service`
    1. Edit configuration parameters in accordance with your local environment setup
1. `$ composer db:migrations:run`

## Using the mysql server from host (localhost)

*You will need a mysql client, be it console one or GUI like SequelPro.

```
mysql -u root -p root -h 127.0.0.1 -P 6607
```

## Using different hostname(s)

Nginx will respond to any hostname, adding values to `/etc/hosts` will work just like with brew variety.

## PHPStorm xdebug integration

Detailed setup can be found on this url: https://dev.to/brpaz/docker-phpstorm-and-xdebug-the-definitive-guide-14og
Bare in mind that we used 9001 port 
