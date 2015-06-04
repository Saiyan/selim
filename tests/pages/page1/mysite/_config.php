<?php

global $project;
$project = 'mysite';
global $database;
$database = '';

// Set the site locale
i18n::set_locale('en_US');

Security::setDefaultAdmin('page1', 'page1');
