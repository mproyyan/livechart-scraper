<h1 align="center"> Livechart Scraper </h1>

## Introduction
Livechart Scraper is a simple **RESTful** application that provide anime information. All resources we got from [livechart](https://livechart.me) through the [web scraping](https://en.wikipedia.org/wiki/Web_scraping) method

## Credits
- Thanks to https://github.com/yusuftaufiq/laravel-books-api because this project i got to know what is web scraping and i'm interested to making it.
- Thanks to https://www.livechart.me for providing resources

## Purpose
This application was created to learn about web scraping (this is my first app focused on web scraping technique) and learn Laravel more deeply.

## API Documentation
You can read API documentation [here](https://mproyyan.github.io/livechart-scraper/)

## Requirements
- PHP 8.1
- Composer
- RDBMS (such as SQLite, PostgreSQL, MySQL etc)
- Redis

## Instalation
- Clone the respository `git clone https://github.com/mproyyan/livechart-scraper.git`
- Change directory `cd livechart-scraper`
- Copy environment file `cp .env.example .env`
- Configure your database connection in `.env` file
- Configure your `DB_CONNECTION` and `DB_DATABASE` in `phpunit.xml` for database testing
- Install all dependencies `composer install`
- Run the migration using `php artisan migrate`
- Make sure you have already installed redis on your computer
- If you use Laravel development server run the application using `php artisan serve` and if you use [Laragon](https://laragon.org) you can skip this step

## Tech Stack
- [PHP 8.1](https://www.php.net/releases/8.1/en.php) - Language syntax
- [Laravel 9](https://laravel.com/docs/9.x/) - Framework
- [Redis](https://redis.io/) - Caching
- [MySQL](https://www.mysql.com/) - Database

## Information
**This application is not hosted yet** so you cant try by hitting the endpoint if you want to try you must **install and run locally** on your computer