# RSS Reader

This application is a self-hosted RSS aggregator and reader.

[![Build Status](https://travis-ci.org/RemyG/RSSReader.svg?branch=develop)](https://travis-ci.org/RemyG/RSSReader)
[![Coverage Status](https://coveralls.io/repos/RemyG/RSSReader/badge.png?branch=develop)](https://coveralls.io/r/RemyG/RSSReader?branch=develop)

## Requirements

To run this application, you need:

* PHP 5.1 or greater with support of PDO
* MySql drivers for PDO
* the Mod_Rewrite module for Apache
* the php5-tidy package
* Composer for the installation

## Installation

### Download the project

#### With git

```
$ mkdir /var/www/rss-reader
$ cd /var/www/rss-reader
$ git clone https://github.com/RemyG/RSSReader.git .
```

#### Without git

Download the application ([ZIP file](https://github.com/RemyG/RSSReader/archive/master.zip) or ), and extract.

### Install with Composer

This is a Composer project. To install Composer, follow the directives [here](https://getcomposer.org/doc/00-intro.md).

Then go to the project root and run:
```
composer install
```

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

## License

This project is released under the MIT License:

Copyright (c) 2013-2017 Rémy Gardette

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

## Changelog

**v0.4**

**v0.3**

**v0.2.0**

* jQueryMobile mobile website
* jQueryUI sortable feeds list
* preferences saving
* keyboard navigation
* old entries clean-up

**v0.1**

* First version of the project
