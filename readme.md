[![Build Status](https://api.travis-ci.org/DevlessTeam/DV-PHP-CORE.svg)](https://travis-ci.org/DevlessTeam/DV-PHP-CORE)
 
##Devless Api Engine(DAE)
The key words “MUST”, “MUST NOT”, “REQUIRED”, “SHALL”, “SHALL NOT”, “SHOULD”, “SHOULD NOT”, “RECOMMENDED”, “MAY”, and “OPTIONAL” in this document are to be interpreted as described in [RFC 2119](https://tools.ietf.org/html/rfc2119).

Versioning ot this project follows the  [Sermantic Versioning Specification](http://semver.org/)

Coding standards are also based on the [PSR-2-coding-style-guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)

The key words “MUST”, “MUST NOT”, “REQUIRED”, “SHALL”, “SHALL NOT”, “SHOULD”, “SHOULD NOT”, “RECOMMENDED”, “MAY”, and “OPTIONAL” in this document are to be interpreted as described in [RFC 2119](https://tools.ietf.org/html/rfc2119).

Versioning ot this project follows the  [Sermantic Versioning Specification](http://semver.org/)

Coding standards are also based on the [PSR-2-coding-style-guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)

**Devless Api Engine(DAE)** is an opensource api engine that allows  crud access to databases as well as execute php scripts and rules . 

Current implementation of the devless api engine is in php and ontop of the laravel framework. 

**DAE** can be used as a standalone (accessed solely via api calls ) however a management console is provided to interact with the api engine.



**Requiments**
* Database (mysql, postgres, sqlsrv etc..)
* An HTTP server
* PHP >= 5.5.9
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension
* composer

**Installation procedure**
* Clone the repo (git clone https://github.com/DevlessTeam/DV-PHP-CORE.git) 
* cd ../DV-PHP-CORE
* run composer install to grab dependecies
* copy .env.example to .env and update the database options 
* run migrations with php artisan migrate
* `` php artisan serve``

If everything goes on smoothly you should be able to access the setup screen at localhost:8000

If you will need help setting up you may check out the laravel [installation](https://laravel.com/docs/5.1) guide as the devless core is based of laravel. 

You may also seek help from the [devless forum](forum.devless.io) and also get started with the [devless application](docs.devless.io)
## How to contribute 
** Please checkout the [Contribute](https://guides.github.com/activities/contributing-to-open-source/) guide

* We would love to hear from  you email us @ info@devless.io
* Also forget to visit our landing page @ [devless.io](devless.io)
