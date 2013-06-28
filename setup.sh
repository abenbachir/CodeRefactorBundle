#!/usr/bin/env sh
# @author Abderrahmane Benbachir <anis.benbachir@gmail.com>
# here we build everything with defaults

#cp app/config/parameters.yml.dist app/config/parameters.yml

echo 'php app/console cache:clear'
	php app/console cache:clear
echo 'php app/console doctrine:schema:drop --force'
	php app/console doctrine:schema:drop --force
echo 'php app/console doctrine:database:drop --force'
	php app/console doctrine:database:drop --force
echo 'php app/console doctrine:database:create'
	php app/console doctrine:database:create
echo 'php app/console doctrine:schema:update --force'
	php app/console doctrine:schema:update --force
	
#echo 'php app/console doctrine:fixtures:load'
#php app/console doctrine:fixtures:load

echo 'php app/console assets:install web'
	php app/console assets:install web
echo 'php app/console assets:install web --symlink'
	php app/console assets:install web --symlink
echo 'php app/console assetic:dump --force'
	php app/console assetic:dump --force
echo 'rm -rf app/cache/*'
	rm -rf app/cache/*
echo 'php app/console cache:clear'
	php app/console cache:clear
setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs

#echo 'bin/phpunit'
#bin/phpunit -c app

#echo 'bin/behat-ci.sh'
#./bin/behat-ci.sh
