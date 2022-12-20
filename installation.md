# Installation instructions for a development environment

### Prerequisites
The application is meant to be run on a Linux machine.
Since it is using docker, you will need to install docker and docker-compose. You can find the installation instructions for your OS [here](https://docs.docker.com/get-docker/).

### Setting up the project
1. Clone the project from GitHub.
2. Make a copy of the file *.env.example* and rename it to *.env*. Change the configuration as needed.
3. Make a copy of the file *docker-compose-dev.yml* and rename it to *docker-compose.yml*.
4. Execute the bash script *install-js-dependencies.sh* to install the JavaScript dependencies.

After that, you can run the container with the following command:
```bash
docker compose up -d
```

### After the container is up and running for the first time
1. Get into the container : `docker exec -it laravel bash`
2. Install the PHP dependencies : `composer install`
3. Generate the app key required by laravel with : `php artisan key:generate`
4. Migrate the database with : `php artisan migrate`
5. Seed DB with test values : `php artisan db:seed`

You can now access the application at http://localhost.


Please make sure that the webserver can create a file for client sessions (test to log in and logout).
See <a href="#possibles-issues">Possibles issues</a> for more information.

The default login is :
- Username: e@e.e
- Password: 123

**The installation is now complete.**

### Stopping and starting the container

To stop the container, run the following command:
```bash
docker compose down
```

To start the container again, run the following command:
```bash
docker compose up -d
```

### Possibles issues
#### Permission denied
Sometimes, the webserver will not be able to create a file for client sessions. This is because the webserver is running as a different user than the one that created the files.
You will probably see an error like this in the browser:
```
The stream or file "[...]" could not be opened in append mode: Failed to open stream: Permission denied` 
```

To fix this, you need to set the owner of the files to the user/group that laravel is using. If you are using the default configuration, the user id and group id are 1001. You can change the user id and group id in the `.env` file.
Here is an example of how to change the owner of the files:
```bash
sudo chown -R 1001:1001 src/storage/
```