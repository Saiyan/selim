selim
=====
CLI Tool for scanning Silverstripe-CMS Installations

[![Build Status](https://travis-ci.org/Saiyan/selim.svg?branch=master)](https://travis-ci.org/Saiyan/selim)
[![Code Climate](https://codeclimate.com/github/Saiyan/selim/badges/gpa.svg)](https://codeclimate.com/github/Saiyan/selim)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Saiyan/selim/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Saiyan/selim/?branch=master)


## Requirements

- composer
- symfony/yaml: v2.6.1
- pear/console_table: 1.2.1


## Installation

```
git clone https://github.com/Saiyan/selim.git
cd selim
composer install
```

Or 

## Commands

```
php bin/selim.php  command [options] [arguments]
```

Before you can start to use selim you have to tell it where your silverstripe folders are.
For this you can use the two commands "find" and "add". 

### find
```
php bin/selim.php find PATH
```
This command searches for all possible paths of silverstripe project-folders.
After that selim will prompt you for every found folder which name you want to assign to each folder.

### add
```
php bin/selim.php add <NAME> </PATH/TO/MYSITE/>
```

The NAME for your site is just a String so you can identify your site later on whereas the PATH needs to be the path to the "project" folder of your silverstripe instance.
If you dont change it after installing Silverstripe it should be the "mysite" folder.

### rm
If you want to remove a page "NAME" from your config then just use

```
php bin/selim.php rm NAME
```

### security
this command reads the Version of your site "NAME" and shows all security vulnerabilities known for this version   

```
php bin/selim.php security NAME
``` 

### start

This is the main command to use with selim. "start" lists all sites that you added and their properties.

```
php bin/selim.php start [OPTIONS]
```

#### options
#####--format

if you start selim with a parameter which starts with "--format=" the analyzer will print every site in the specified format.  
the standard-format-string looks something like this (without the line breaks) 

```
Site:            %s%n
Version:         %v%n
DefaultAdmin:    %da%n
EmailLogging:    %el%n
EnvironmentType: %et%n
Modules:         %mo%n
```

You can use the following placeholders in your format:
```
%n    //Newline
%s    //Sitename
%v    //Version
%da   //DefaultAdmin
%el   //EmailLogging
%et   //EnvironmentType
%mo   //Modules
%cfgp //Path to _config.php in your project folder
%cfgy //Path to _config/config.yml in your project folder
%root //Root directory of your Silverstripe-CMS
```

some examples
```
 #List the names of all sites and the path to their _config.php
php bin/selim.php start --format=%s %cfgp%n

 #List the names of all sites and the their version
php bin/selim.php start --format=%s %v%n
```

#####--filter-name

if you don't want to see all your sites listed you can filter the results shown to you with --filter-name=
Everything after the parameter name will be interpreted as a Regular Expression and will be tested against the name of the site.
 
Some Examples
```
//list every site which starts with the string "site"
--filter-name=^site

//list every site which name ends with the string "site"
--filter-name=site$

//list only sites which have only characters in their name  
--filter-name=\w+
```

#####--filter-module
```
//list only sites with userforms module  
--filter-module=userforms
```

#####--filter-da
```
//list only sites which use Security::setDefaultAdmin   
--filter-da
```

#####--filter-env
```
//list only sites which have a specific environment type set (dev,live or test)   
--filter-env=dev
```

### global options
#### --config=

Naturally selim uses/generates the config.json file in its own directory. If you want to use another config-file you can use the --config parameter 

```
--config=/home/user/temp/my-selim-config-file
```

##Build
Building a phar for selim is easy.
Get the Repository.
Use composer to install all dependencies.
Let Box do all the work.(http://box-project.org/)
```
git clone https://github.com/Saiyan/selim.git selim-build
cd selim-build
composer global require "kherge/box:~2.0"
~/.composer/vendor/kherge/box/bin/box build -v
#Composer under Windows uses the following path 
#~\AppData\Roaming\Composer\vendor\kherge\box\bin\box
```
