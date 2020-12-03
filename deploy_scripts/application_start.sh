echo "start docker container service"
cd /var/www/laradock
docker-compose up -d --build nginx
