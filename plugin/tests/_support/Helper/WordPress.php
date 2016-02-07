<?php
namespace Helper;
// here you can define custom actions
// all public methods declared in helper class will be available in $I

class WordPress extends \Codeception\Module
{
    private $path_to_wordpress = '/var/www/html/';
    private $database_name = 'wordpress';
    private $database_host = 'db';
    private $database_user = 'root';
    private $database_pass = 'example';

    public function _beforeSuite($settings = []) {
        $this->install_wordpress();
        $this->install_plugin();
    }

    private function install_wordpress() {
        if (!file_exists("{$this->path_to_wordpress}wp-config-sample.php")) {
            exec("wp core download --path={$this->path_to_wordpress}");
        }

        if (!file_exists("{$this->path_to_wordpress}wp-config.php")) {
            exec("wp core config --path={$this->path_to_wordpress} --dbname={$this->database_name} --dbuser={$this->database_user} --dbpass={$this->database_pass} --dbhost={$this->database_host}");
        }
    }

    private function install_plugin() {
        $plugin_name = getenv('PLUGIN_NAME');
        exec("ln -sf /data/{$plugin_name}/ {$this->path_to_wordpress}/wp-content/plugins");
        exec("wp plugin activate --path={$this->path_to_wordpress} {$plugin_name}");
    }
}
