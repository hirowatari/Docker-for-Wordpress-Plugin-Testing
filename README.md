# [Docker](//docker.com) image for testing WordPress plugins.

A boilerplate for creating a containerized plugin testing environment using [Codeception](//codeception.com).

## Set up
1. Create your plugin in /data/[your-plugin-name]
2. Set your PLUGIN_NAME (that matches the plugin name in step 1) in the docker-compose.yml
3. `./docker-build.sh`
4. `docker-compose up`
5. `docker-compose run wordpress codecept build`

## Run tests
1. `docker-compose run wordpress codecept run`

## Note
WordPress user credentials are
- Username: a
- Password: a
- E-mail: a@a.a
This dump.sql was generated only with the information `wp core install --path=/var/www/html --url=wordpress --title='just a wordpress site' --admin_user=a --admin_password=a --admin_email='a@a.a' --skip-email`