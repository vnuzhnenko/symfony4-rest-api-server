TODO app API with Symfony4
==========================

To run application:
-------------------

* docker-compose up
* docker exec todo_app bin/console doctrine:migrations:migrate
* docker exec todo_app bin/phpunit -c phpunit.xml.dist tests
