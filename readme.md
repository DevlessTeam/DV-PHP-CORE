<h1 align="center">
    <a href="https://www.devless.io">
        <img src="https://www.devless.io/img/logo.png" alt="devless" height="100">
    </a>
</h1>
<p align="center">
  <a href="https://travis-ci.org/DevlessTeam/DV-PHP-CORE">
    <img src="https://api.travis-ci.org/DevlessTeam/DV-PHP-CORE.svg?branch=master"
         alt="devless">
  </a>
</p>
<br>

## Devless BAAS Service
[Devless](https://www.devless.io) provides the perfect starting point for your next big idea. Don't worry about configuring your backend and focus on what really matters **getting your product out there** while focusing on your **users**




## Table of Content
* Key Features
* Installation
  * Heroku
  * Using Docker
  * Local
* How It Works
   * Creating Tables
   * Setting Privacy for Users
   * Services
* Question and Support
* How to Contribute

## Key Features

* Easy configurable **GUI** to setup.
* Console to query your data.
* Privacy on which table to make available for users.
* Get services that make workflow easy.


## Installation
Devless was built to make life easier for developers to build their backend without going through any form of stress.

### Deploy to Heroku 

Deploying Devless to heroku is click away.
[![Deploy to Heroku](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy?template=https://github.com/DevlessTeam/DV-PHP-CORE/tree/heroku2)
We have done all the heavy lifting for you.

![heroku-deploy](https://user-images.githubusercontent.com/28383750/29027985-5d8aecae-7b72-11e7-8351-f52787e634ea.gif)

### Using Docker

To install devless using docker <br>
**Make** sure you have devless installed in your working environment
Check [here]() on how to install docker.

You can now install using devless docker [image]()

### Local Installation

<!-- **Requirement** -->

* **Requirement**

    * Database (MySQL, PostgreSQL, SQLSRV etc..)
    * An HTTP server
    * PHP >= 5.5.9
    * OpenSSL PHP Extension
    * PDO PHP Extension
    * Mbstring PHP Extension
    * Tokenizer PHP Extension
    * Composer

* **Installation Procedure**

```bash

// Git clone devless repository 
git clone https://github.com/DevlessTeam/DV-PHP-CORE.git

// Change into the directory
cd ../DV-PHP-CORE

// Grab dependencies run
composer install

// Copy .env-example to .env and Update the database options
cp .env-example .env

// Run migrations
./devless migrate or 
./devless serve

```

When you are done you can log onto localhost:8080 to access the setup screen

## How It Works


## Questions and Support
Follow or join these channels for questions and support, and to keep updated on latest releases and announcements.

<div style="display: flex;">
    <div style="flex:1;margin-right: 10px;">
        <a href='https://slack.devless.io' target="_blank">
            <img src='https://raw.githubusercontent.com/gliechtenstein/images/master/slack_smaller.png'>
        </a>
    </div>
    <div style="flex:1">
        <a href='https://www.twitter.com/devlessio' target="_blank">
            <img src='https://raw.githubusercontent.com/gliechtenstein/images/master/twitter_smaller.png'>
        </a>
    </div>
</div>

If you want to go the **old fashioned** west you can shoot us an email
**<info@devless.io>**

## How to contribute 

**Please checkout the [Contribute](https://guides.github.com/activities/contributing-to-open-source/) guide on how to contribute**
No contribution is too small 

Also don't forget to visit our landing page ⇒ [devless.io](https://devless.io)
