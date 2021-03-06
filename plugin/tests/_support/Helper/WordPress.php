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
        $this->activate_plugin(self::plugin_name());
    }

    private function install_wordpress() {
        if (!file_exists("{$this->path_to_wordpress}wp-config-sample.php")) {
            exec("wp core download --path={$this->path_to_wordpress}");
        }

        if (!file_exists("{$this->path_to_wordpress}wp-config.php")) {
            exec("wp core config --path={$this->path_to_wordpress} --dbname={$this->database_name} --dbuser={$this->database_user} --dbpass={$this->database_pass} --dbhost={$this->database_host}");
        }
        // running wp core is-installed didn't seem to return anything
        // trying to install, when already installs, just skip installing
        exec("wp core install --path={$this->path_to_wordpress} --url=wordpress --title='just a wordpress site' --admin_user=a --admin_password=a --admin_email='a@a.a' --skip-email");
    }

    public static function plugin_name() {
        return getenv('PLUGIN_NAME');
    }

    public static function plugin_path() {
        $plugin_name = self::plugin_name();
        return "/wp-content/plugins/{$plugin_name}";
    }

    private function activate_plugin($plugin_name) {
        exec("ln -sf /data/{$plugin_name}/ {$this->path_to_wordpress}/wp-content/plugins");
        exec("wp plugin activate --path={$this->path_to_wordpress} {$plugin_name}");
    }
}
