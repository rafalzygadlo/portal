#sudo docker exec livewire.php composer install
sudo docker exec livewire.php php artisan migrate:fresh --seed
