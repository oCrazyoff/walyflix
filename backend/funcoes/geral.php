<?php

if (!defined('BASE_URL')) {
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        define('BASE_URL', '/walyflix/');
    } else {
        define('BASE_URL', '/');
    }
}
?>