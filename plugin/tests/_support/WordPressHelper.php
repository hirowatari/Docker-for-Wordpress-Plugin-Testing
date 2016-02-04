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
    private $database_host = 'db';
    private $database_user = 'root';

    public function _beforeSuite() {
        if (!file_exists("{$this->path_to_wordpress}wp-config-sample.php")) {
            exec("wp core download --path={$this->path_to_wordpress}");
        }
        $db = new \mysqli($this->database_host, 'root', $_ENV['DB_ENV_MYSQL_ROOT_PASSWORD']);
        $db->prepare("CREATE DATABASE {$this->database_name};")->execute();

        exec("wp core config --path={$this->path_to_wordpress} --dbname={$this->database_name}
                --dbuser={$this->database_user} --dbpass={$_ENV['DB_ENV_MYSQL_ROOT_PASSWORD']}
                --dbhost={$this->database_host}");
        exec('wp core install --url=localhost --title="just a wordpress site" --admin_user=a
            --admin_password=a --admin_email="a@a.a" --skip-email');
    }

    public function _afterSuite() {
        $db = new \mysqli('db', 'root', $_ENV['DB_ENV_MYSQL_ROOT_PASSWORD']);
        $db->prepare("DROP DATABASE {$this->database_name};")->execute();
    }
}