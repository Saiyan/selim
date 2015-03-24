<?php

require_once __DIR__ . '/../autoload.php';
spl_autoload_register(array('AutoLoader', 'loadClass'));

require_once 'helper/TestPage.php';

register_shutdown_function(function() {
    if(file_exists('config.json')) {
        unlink('config.json');
    }
});