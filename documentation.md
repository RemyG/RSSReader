---
layout: page
title: Documentation
---

# {{ page.title }}
			
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
	
		$ mkdir /var/www/rss-reader
		$ cd /var/www/rss-reader
		$ git clone https://github.com/RemyG/RSSReader.git .

* Fetch the submodules Propel ORM and SimplePie:

		$ git submodule init
		$ git submodule update

#### Without git

* Download the application, and extract.
* Download [Propel](http://propelorm.org/download.html) and extract in `application/plugins/propel`.
* Download [SimplePie](http://simplepie.org/downloads/) and extract in `application/plugins/simplepie`.

### Configuration

* Go to `http://your_url/installation.php` in a browser.
* Fill the form with the correct infomation:
	* Site Base URL: the base URL of the application
	* DB Host: the database host URL
	* DB Name: the database name
	* DB Username: the database account username
	* DB Password: the database account password	
* Go to `BASE_URL` in a browser, and create the main user as asked.
* You're good to go!