#!/bin/sh
#
# Run tests
#

parent_path=$( cd "$(dirname "${BASH_SOURCE[0]}")" ; pwd -P )
cd "$parent_path"

echo ""
echo "\033[92m###################################### Run tests ######################################\033[m"
echo ""

echo "\033[94m[INFO]\033[m Boot test environment"
docker-compose up -d

echo "\033[94m[INFO]\033[m Check composer version"
docker-compose exec php composer --version

echo "\033[94m[INFO]\033[m Install composer dependencies"
docker-compose exec php composer update

# todo
#echo "\033[94m[INFO]\033[m Build test environment"

echo ""
echo "\033[92mDone.\033[m"
echo ""
