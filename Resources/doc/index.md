Getting Started With XiBundleFilelib
====================================

The XiBundleFilelib bundle allows you to use [XiFilelib](https://github.com/xi-project/xi-filelib) in a Symfony2 project.

## Installation

### 1. Install dependencies


Add the following lines to your composer.json file:

* "xi/filelib": "0.11.*"
* "xi/filelib-bundle": "0.11.*"

then run

`php composer.phar update`

Add

`new \Xi\Bundle\FilelibBundle\XiFilelibBundle()`

to AppKernel.php

### 2. Create required directories

* app/data/files
* app/data/temp
* web/files

### 3. Set proper file permissions

sudo chown -R www-data app/data/files
sudo chown -R www-data app/data/temp
sudo chown -R www-data web/files
