---
layout: page
title: Download
---

# {{ page.title }}

## With Git

* Clone the main project:

		$ mkdir /var/www/rss-reader
		$ cd /var/www/rss-reader
		$ git clone https://github.com/RemyG/RSSReader.git .

* Fetch the submodules Propel ORM and SimplePie:

		$ git submodule init
		$ git submodule update

## Without git

* Download the application ([ZIP File](https://github.com/RemyG/RSSReader/zipball/master) or [TAR Ball](https://github.com/RemyG/RSSReader/tarball/master)), and extract.
* Download [Propel](http://propelorm.org/download.html) and extract in `application/plugins/propel`.
* Download [SimplePie](http://simplepie.org/downloads/) and extract in `application/plugins/simplepie`.