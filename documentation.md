---
layout: page
title: Documentation
---

# {{ page.title }}

This application is a self-hosted RSS aggregator and reader.

## Requirements

To run this application, you need:

* PHP 5.1 or greater with support of PDO
* MySql drivers for PDO
* the Mod_Rewrite module for Apache

## Installation

### Download the project

See [the download page]({{ site.baseurl }}/download.html).

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