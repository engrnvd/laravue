# Lara Vue

## Setting up the project:

 - Set UP your `.env` file. (You can copy and paste `.env.example` to get an idea)

    You will also need to set an environment file for the frontend app:
            
        cd ./frontend
        cp env.example.js env.js

 - Run `./setup.sh` in the project root.

    If you are unable to run the shell script for some reason, run the following commands:

        composer install
        php artisan migrate
        php artisan db:seed
        php artisan storage:link
        cd ./frontend
        npm i
 - Please note that the project uses mongodb as the database so you may have to set up mongodb php extension on your system: https://www.php.net/manual/en/mongodb.installation.php

## Running the project

Run `./run.sh` in the project root.

If you are unable to run the shell script for some reason, run the following commands:

    php artisan serve
    cd ./frontend
    npm run serve
