# Health checker incident REST microservice

[![License](https://img.shields.io/github/license/tonicforhealth/health-checker-incident.svg?maxAge=2592000)](LICENSE.md)
[![Build Status](https://travis-ci.org/tonicforhealth/health-checker-incident.svg?branch=master)](https://travis-ci.org/tonicforhealth/health-checker-incident)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tonicforhealth/health-checker-incident/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tonicforhealth/health-checker-incident/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/tonicforhealth/health-checker-incident/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tonicforhealth/health-checker-incident/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/9b7115c0-9bb5-45e2-b460-98af3b856c6c/mini.png)](https://insight.sensiolabs.com/projects/9b7115c0-9bb5-45e2-b460-98af3b856c6c)

This service allows Health board system to use interface for aggregating incidents and performs notification transmit for heterogeneous subjects.

## Requirements
------------

- PHP 5.5 or higher              
- ext-pdo_sqlite
- ext-imap

## Installation using [Composer](http://getcomposer.org/)
------------

```bash
composer create-project tonicforhealth/health-checker-incident
```

## Setup
------------

For the correct run of health-checker-incident app you need to install cachet:
       
 - [Cachet](https://github.com/CachetHQ/Cachet)

Then set up config for it and other subject items app/config/parameter.php:
       
    ...
    incident.notifications.subjects:
        file:
            file1:
                target: 'fileName'
        request:
            cached:
                target: 'http://localhost:8000/api/v1'
        email:
            drefixs:
                schedule: '* * 0,1,3,5,7,9,11,13,15,17,19,21,23,25,27,29,30,31 * * *'
                target: email@example.com
    ...

## Run of the app using symfony built in server
------------

```bash
bin/console server:run
```

## Run of the app with docker-compose
------------

```bash
git clone git@github.com:tonicforhealth/health-checker-incident-docker.git
cd health-checker-incident-docker
cp config/parameters.default.yml config/parameters.yml
vi config/parameters.yml # set up right config
docker-compose up -d incident-web
```

## What notification types it has:
------------
- file
- request (cachet)
- email
