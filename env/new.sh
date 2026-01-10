cd ../
rm -rf *
rm -rf .*

docker exec app.maxkod.php composer create-project laravel/laravel .

chmod 777 -R bootstrap/cache
chmod 777 -R storage
