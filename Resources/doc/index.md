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

* `sudo chown -R www-data:www-data app/data/files`
* `sudo chown -R www-data:www-data app/data/temp`
* `sudo chown -R www-data:www-data web/files`
* `sudo chmod -R ug+rw app/data/files`
* `sudo chmod -R ug+rw app/data/temp`
* `sudo chmod -R ug+rw web/files`

Replace www-data with whatever user/group you want to use.

### 4. Routing

Add

```
xi_filelib:
    resource: "@XiFilelibBundle/Resources/config/routing.yml"
    prefix: /
```
to app/config/routing.yml

### 5. Create Database tables

XiFilelibBundle currently support:

* MariaDB/Mysql
* PostgreSQL
* sqlite

Currently there are no tools for this and you must generate them yourself.

You can find the schemas in vendor/xi/filelib/docs.

## Configuration

[XiFilelib](https://github.com/xi-project/xi-filelib) is highly configurable and with XiBundleFilelib the settings are easily configurable through `app/config/config.yml`.

In more advanced situations you can also override custom implementations with your own.

## Example

You can use [Xi Filelib Symfony sandbox](https://github.com/xi-project/xi-filelib-symfony-sandbox) for setting up a very simple Symfony 2 project which shows some of the XiBundleFilelib features and use it for further explorations of XiBundleFilelib.