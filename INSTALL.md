# Install Docker and Docker-compose on clean ubuntu:

###add the new gpg key
apt-key adv --keyserver hkp://p80.pool.sks-keyservers.net:80 --recv-keys 58118E89F3A912897C070ADBF76221572C52609D
### add new repo
add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"

apt-get update
### remove old docker
apt-get purge lxc-docker*
### install NEW docker
apt-get install docker-engine

# Install docker compose
curl -L https://github.com/docker/compose/releases/download/1.18.0/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
docker-compose --version

# Clone
cd /var/www/
mv html old_html
git clone https://github.com/joe1248/elastic1.git
mv elastic1 html

# Build and launch
docker build -t elastic_image .
docker-compose up --build

# Get inside the apache server 
winpty docker exec -ti  elastic1_elastic_web_server_1 bash


# Prepare DB
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate -n
# Load fixtures
php bin/console doctrine:fixtures:load -n


# To apply DB annotations changes to DB: 
php bin/console doctrine:schema:update --force
php bin/console doctrine:schema:update --sql-dump
# To generate new diff, from latest db changes
php bin/console doctrine:migrations:diff
