Log file entries management service, built using : PHP 8.0.0 | SYMFONY 5.4.4 | MYSQL 5.7.22.

1. In the app folder run : "docker-compose up -d" to build and compose the images.
2. In the site folder run : "composer install" to install framework components.
3. Set the log file path variable in the ".env" file.
4. Open the app on : "http://127.0.0.1:8001".
5. Create the database by running : "php bin/console doctrine:database:create".
6. Architect the database by running : "php bin/console doctrine:migrations:migrate".
7. Start the service by running : "php bin/console app:manage_log".
Probably you will face an issue due the docker environment, in the .env file use the 1st database url, and relaunch the command.
After that, in order to be able to consume the /count api, comment the first database url and use the second.
The service will remain capturing changes in the .log file, and the api will stay accessible.
