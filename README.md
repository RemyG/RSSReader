# RSS Reader

This application is a self-hosted RSS agregator and reader.

## Requirements

To run this application, you need:

* PHP 5.1 or greater with support of PDO
* MySql drivers for PDO
* the Mod_Rewrite module for Apache
* the php5-tidy package

## Installation

### Download the project

#### With git

* Clone the main project:

    mkdir /var/www/rss-reader
    cd /var/www/rss-reader
    git clone https://github.com/RemyG/RSSReader.git .

* Fetch the submodules Propel ORM and SimplePie:

    git submodule init
    git submodule update

#### Without git

* Download the application, and extract.
* Download [Propel](http://propelorm.org/download.html) and extract in `application/plugins/propel`.
* Download [SimplePie](http://simplepie.org/downloads/) and extract in `application/plugins/simplepie`.

### Configuration

* Edit the file `application/config/config.php` and fill in your `BASE_URL`.
* Import the files `application/build/sql/schema.sql` and `application/build/sql/insert.sql` with your MySql browser ([PHPMyAdmin](http://www.phpmyadmin.net), [SQL Buddy](http://sqlbuddy.com/),...).
* Go to `BASE_URL` in a browser, and create the main user as asked.
* You're good to go!

## Utilisation


