selim
=====
CLI Tool for scanning Silverstripe-CMS Installations

[![Build Status](https://travis-ci.org/Saiyan/selim.svg?branch=master)](https://travis-ci.org/Saiyan/selim)
[![Code Climate](https://codeclimate.com/repos/548ae469695680418202c0b5/badges/72d27922807d5d2a1476/gpa.svg)](https://codeclimate.com/repos/548ae469695680418202c0b5/feed)



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

## Usage
```
php selim.php [COMMAND] [OPTIONS]
```

If selim is started without a command it will print any configured Silverstripe-CMS instances found in the config.json file.
When you start selim for the first time there will be no config.json so you need to add a new Silverstripe site with:

```
php selim.php add <NAME> </PATH/TO/MYSITE/>
```

The NAME for your site is just a String so you can identify your site later on whereas the PATH needs to be the path to the "project" folder of your silverstripe instance.
If you dont change it after installing Silverstripe it should be the "mysite" folder.

If you want to remove a Page from your config then just use

```
php selim.php remove <NAME>
```

## Options
###--format=

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

###--filter-name=

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


##Build
Building a phar for selim is easy.
Get the Repository.
Use composer to install all dependencies.
Let Box do all the work.(http://box-project.org/)
```
git clone https://github.com/Saiyan/selim.git selim-build
cd selim-build
php composer.phar install
vendor/kherge/box/bin/box build -v
```
