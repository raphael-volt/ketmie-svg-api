# Ketmie SVG API

## TEMP
```shell
#serve env=test
export APP_ENV=test export APP_RUNTIME_ENV=test; symfony serve

```
{
"email": "raphael@ketmie.com",
"password": "password"
}

## Memo
Create entity using Symfony MakerBundle:
```shell
bin/console make:entity --api-resource
```
Serve 
```shell
symfony serve --port=8000
```
### Create admin with curl :

```shell
# get INSTALL_PASSWORD value
APP_ENV="dev|test|prod" bin/console secrets:reveal INSTALL_PASSWORD

php bin/console secrets:reveal INSTALL_PASSWORD 

# create api_user with role admin
curl -X 'POST' \
'https://127.0.0.1:8000/setup' \
-H 'accept: application/json' \
-H 'Content-Type: application/json' \
-d '{
"email": "admin@example.com",
"password": "password",
"secret":"INSTALL_PASSWORD"
}'
# test
curl -X 'POST' \
'https://127.0.0.1:8000/setup' \
-H 'accept: application/json' \
-H 'Content-Type: application/json' \
-d '{
"email": "admin@test.com",
"password": "password",
"secret":"p@ssword"
}'



curl -X 'POST' \
'https://api2.ketmie.com/setup' \
-H 'accept: application/json' \
-H 'Content-Type: application/json' \
-d '{
"email": "stephanie@example.com",
"password": "nelson2001",
"secret":"cnobmyct0@INSTALL"
}'


```
# run configuration
### @TODO
- Why the setup endpoint is not available in swagger docs ?

- create symbol groups

## PROD MySQL  v8.4

ketmieapi <PWD>KC

## MySQL CLI
```mysql -h localhost -P 8090 --protocol=tcp -u root -padmin ketmie-api```

## Migrations
@TODO Why migrations fail ?

## MySQL memo
```mysql
# get admins
SELECT roles, email
FROM api_user
WHERE JSON_CONTAINS(roles, '"ROLE_ADMIN"');
insert into api_user (email, roles, password)
values ('admin@ketmie.com', '["ROLE_ADMIN", "ROLE_USER"]', 'HASHED_PASSWORD')

# DB DUMP
mysqldump -u root -padmin --databases ketmie-api -P 8090 -h 127.0.0.1 > api.sql
```
