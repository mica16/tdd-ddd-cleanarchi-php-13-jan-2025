rm -rf ./docker-postgresql-data-for-test
docker-compose -f ./docker-compose-postgresql-test.yml down --volumes
docker-compose -f ./docker-compose-postgresql-test.yml down
docker-compose -f ./docker-compose-postgresql-test.yml up
