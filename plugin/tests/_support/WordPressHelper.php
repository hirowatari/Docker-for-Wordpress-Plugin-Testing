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
        $this->install_wordpress();
        $this->install_plugin();
    }

    public function _afterSuite() {
        $this->remove_plugin();
    }

    private function install_wordpress() {
        $db = new \mysqli($this->database_host, 'root', $_ENV['DB_ENV_MYSQL_ROOT_PASSWORD']);
        $db->prepare("DROP DATABASE {$this->database_name};")->execute();
        $db->prepare("CREATE DATABASE {$this->database_name};")->execute();

        if (!file_exists("{$this->path_to_wordpress}wp-config-sample.php")) {
            exec("wp core download --path={$this->path_to_wordpress}");
        }

        if (!file_exists("{$this->path_to_wordpress}wp-config.php")) {
            exec("wp core config --path={$this->path_to_wordpress} --dbname={$this->database_name} --dbuser={$this->database_user} --dbpass={$_ENV['DB_ENV_MYSQL_ROOT_PASSWORD']} --dbhost={$this->database_host}");
        }

        exec("wp core install --path={$this->path_to_wordpress} --url=localhost --title='just a wordpress site' --admin_user=a --admin_password=a --admin_email='a@a.a' --skip-email");
    }

    private function install_plugin() {
        exec("cp -r /data/{$_ENV['PLUGIN_NAME']}/ {$this->path_to_wordpress}/wp-content/plugins");
        exec("wp plugin activate --path={$this->path_to_wordpress} {$_ENV['PLUGIN_NAME']}");
    }

    private function remove_plugin() {
        exec("wp plugin deactivate --path={$this->path_to_wordpress} {$_ENV['PLUGIN_NAME']}");
        exec("wp plugin delete --path={$this->path_to_wordpress} {$_ENV['PLUGIN_NAME']}");
    }
}