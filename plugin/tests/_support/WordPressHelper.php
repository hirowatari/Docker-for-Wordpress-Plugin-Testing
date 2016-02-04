<?php
namespace Codeception\Module;

/**
 * WordPress helper will download and install WordPress if it is not installed then install and activate plugin
 * When it tears down, it will deactivate the plugin and delete it from WordPress,
 * but will not delete the WordPress files.
 */

class WordPressHelper extends \Codeception\Module
{
    private $path_to_wordpress = '/var/www/html/';
    private $database_name = 'wordpress';

    public function _beforeSuite() {
        if (!file_exists("{$this->path_to_wordpress}wp-config-sample.php")) {
            exec("wp core download --path={$this->path_to_wordpress}");
        }
        $db = new \mysqli('db', 'root', $_ENV['DB_ENV_MYSQL_ROOT_PASSWORD']);
        $db->prepare("CREATE DATABASE {$this->database_name};")->execute();
    }

    public function _afterSuite() {
        $db = new \mysqli('db', 'root', $_ENV['DB_ENV_MYSQL_ROOT_PASSWORD']);
        $db->prepare("DROP DATABASE {$this->database_name};")->execute();
    }
}