[![Codacy Badge](https://app.codacy.com/project/badge/Grade/952424dbc1164936ac4434dba39051d5)](https://www.codacy.com/gh/MytiX/TodoList/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=MytiX/TodoList&amp;utm_campaign=Badge_Grade)

# About ToDoList

Openclassroom's project number 8, Refactoring application Symfony version 3.4 to LTS 5.4, add tests and patch bug

# Requirements

To launch the project, you need Docker : https://docs.docker.com/get-docker/

# Get the repository

Clone repository
```
git clone https://github.com/MytiX/TodoList
```

# Start Application
Start all container :
```
docker-compose up -d
```
Create database :
```
docker-compose exec -u www-data www php bin/console doctrine:database:create
```
Install migration :
```
docker-compose exec -u www-data www php bin/console doctrine:migrations:migrate --no-interaction
```
Install fixtures :
```
docker-compose exec -u www-data www php bin/console doctrine:fixtures:load --no-interaction
```
Run tests :
```
docker-compose exec -u www-data www make phpunit
```
## Localhost
App :
```
http://localhost:8741
```
phpMyAdmin :
```
http://localhost:8761/index.php
```
## User Access
Admin :
```
username : Admin
password : testtest
```
User :
```
username : Test
password : testtest
```