# Tunggu database siap sebelum serve
CMD php -r "
    echo 'Waiting for database...'; 
    while (!@fsockopen(getenv('DB_HOST'), getenv('DB_PORT'))) { 
        sleep(1); 
    } 
    echo 'Database is ready!';
" \
&& php artisan migrate --force || true \
&& php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
