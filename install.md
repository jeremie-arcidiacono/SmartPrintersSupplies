# Installation instructions for an development environment

### Prerequisites
1. Install docker
2. Install php8.1
3. Install php composer
4. Install php8.1-curl, php8.1-zip

### Installation
1. Install project dependency with composer
2. Download JS library and place them in public folder (public/js/lib)
    - jQuery 3.6.0
    - Chart.js 3.9.1
    - [randomColor 0.6.1](https://github.com/davidmerfield/randomColor)
3. Generate app key with : `php artisan generate key`
4. Migrate DB with artisan : `php artisan migrate`
5. Seed DB with test values :
    1. Get into the container : `docker exec -it laravel bash`
    2. Execute seeder : `php artisan db:seed`

### Run
1. Run the container : `docker-compose up -d`

Please make sure that the webserver can create a file for client sessions (test to log in and logout)
