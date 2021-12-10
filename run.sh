printf "\nRunning Backend server...\n\n"
php artisan serve &

printf "\nRunning Frontend Server...\n\n"
cd ./frontend
npm run serve
