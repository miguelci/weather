# Weather

[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/miguelci/weather/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/miguelci/weather/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/miguelci/weather/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/miguelci/weather/?branch=master)


This project fetches data from different weather providers, transforms the data and displays it
in a human readable format.

## Usage

```
docker-compose build
docker-compose up
```
If it is the first time, the service init may take a while to run. Only when it exits 
everything will be ready.

After every dependency is installed a local server should be running on `http://localhost:8080` 
where the application frontend should be running.

A cron is included to reload the data on the backend at every minute.

### Cron Logs

`docker exec -it weather_php-fpm_1 tail -f /var/log/cron.log`

### Tests

`docker-compose run --rm --no-deps php-fpm vendor/bin/phpunit`

### PHPStan

`docker-compose run --rm --no-deps php-fpm vendor/bin/phpstan analyse`

### PHPCs

`docker-compose run --rm --no-deps php-fpm vendor/bin/phpcs`
