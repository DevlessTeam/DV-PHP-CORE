[![Build Status](https://api.travis-ci.org/DevlessTeam/DV-PHP-CORE.svg?branch=master)](https://travis-ci.org/DevlessTeam/DV-PHP-CORE) 
[![Docker Pulls](https://img.shields.io/docker/pulls/eddymens/devless.svg)](https://hub.docker.com/r/eddymens/devless/) 
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/cb7072312ffb482ebb57ca2b6e5cf3a9)](https://www.codacy.com/app/EDDYMENS/DV-PHP-CORE?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=DevlessTeam/DV-PHP-CORE&amp;utm_campaign=Badge_Grade)

## Devless is a tool for ready-made back-end for development of web or mobile applications. It is fully open source under the permissive Apache v2 license. This means that you can develop your front end without worrying about neither back-end code or the business risk of a propitiatory backend-as-a-service.
 
## DevLess provides the perfect starting point for your next big idea. Forget all the boilerplate and focus on what matters: your APPLICATION and USERS its like Laravel Spark and also comes with the ease Parse(RIP) had. https://devless.io/

**How?**

**Well first upto 93% of the things you do on DevLess is pure configuration via GUI**
![Service Image](http://fs5.directupload.net/images/161228/8rrzj4ms.png)

**For example this is how you create tables on DevLess (migration equivalence in frameworks)*
![Tables Image](http://fs5.directupload.net/images/161228/6mzzjt8c.png)

**Deciding on which table to make  available to end users via automagically generated APIs is just a tab away**
![Privacy Image](http://fs5.directupload.net/images/161228/7v3n3nzv.png)
 **Working with Data is a joy**
 ![data table](http://fs5.directupload.net/images/161228/8pzuahgt.png)
**Deployment is a breeze all you have to do is export your app and import it into another DevLess instance on the cloud could be via our [docker image](https://hub.docker.com/r/eddymens/devless/)**

## This part ensures that no shit 💩 is blown into the project
The key words “MUST”, “MUST NOT”, “REQUIRED”, “SHALL”, “SHALL NOT”, “SHOULD”, “SHOULD NOT”, “RECOMMENDED”, “MAY”, and “OPTIONAL” in this document are to be interpreted as described in [RFC 2119](https://tools.ietf.org/html/rfc2119).

The project versioning is based on [Sermantic Versioning Specification](http://semver.org/)

Coding standards are also based on the [PSR-2-coding-style-guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)

## For those ready to take the 💊 or you could just ignore below and use docker 📦
**Devless API Engine(DAE)** is an opensource API engine that allows CRUD access to databases as well as execute PHP scripts and rules. 

Current implementation of the Devless API Engine is in PHP and built on top of the Laravel framework. 

**DAE** can be used as a standalone (accessed solely via API calls) however a management console is provided to interact with the API engine.



**Requirements**
* Database (MySQL, PostgreSQL, SQLSRV etc..)
* An HTTP server
* PHP >= 5.5.9
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension
* Composer

**Installation procedure**
* Clone the repo `git clone https://github.com/DevlessTeam/DV-PHP-CORE.git` 
* Change into directory `cd DV-PHP-CORE`
* run `composer install` to grab dependecies
* copy .env.example to .env `cp .env.example .env` and update the database options
* run migrations with `./devless migrate`
* `./devless serve`

If everything goes on smoothly you should be able to access the setup screen at [localhost:8000](http://localhost:8000)

If you will need help setting up you may check out the Laravel [installation](https://laravel.com/docs/5.1) guide as the Devless core is based off of Laravel.

Alternatively, you can deploy your own copy unto Heroku😎 

[![Deploy to Heroku](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy?template=https://github.com/DevlessTeam/DV-PHP-CORE/tree/heroku3)

Checkout out the [docs](https://devless.gitbooks.io/devless-docs-1-3-0/content/why-devless.html)

## Questions and Support
Follow or join these channels for questions and support, and to keep updated on latest releases and announcements.

<table class='equalwidth follow'>
  <tr>
		<td>
			<a href='https://slack.devless.io' target="_blank">
        <b>Slack</b><br><br>
        <img src='https://raw.githubusercontent.com/gliechtenstein/images/master/slack_smaller.png'>
        <br>
        Join Now >
      </a>
		</td>
		<td>
			<a target="_blank" href='https://www.twitter.com/devlessio'>
        <b>Twitter</b><br><br>
				<img src='https://raw.githubusercontent.com/gliechtenstein/images/master/twitter_smaller.png'>
        <br>
        Follow >
			</a>
		</td>
	</tr>
</table>

## How to contribute 
**Please checkout the [Contribute](https://guides.github.com/activities/contributing-to-open-source/) guide on how to contribute**
No contribution is too small 

* We would love to hear from  you email us ⇒ <info@devless.io>
* Also don't forget to visit our landing page ⇒ [devless.io](https://devless.io)
