-Docker up :
docker-compose up -d nginx mysql phpmyadmin workspace
-Docker stop :
docker-compose stop
-Docker ssh :
winpty docker-compose exec --user=laradock workspace bash
- COnfig for hosts in local machine :
127.0.0.1 usersite.local
127.0.0.1 adminsite.local

-Artisan for admin :
php admartisan key:generate
-Artisan normal :
php artisan key:generate
- database :
host :  mysql
root
root

