TODO app API with Symfony4
==========================

To run application:
-------------------

* docker-compose up
* docker exec todo_app bin/console doctrine:migrations:migrate --no-interaction
* docker exec todo_app bin/phpunit -c phpunit.xml.dist tests

TODO:
-----

* Use ACL via Symfony voters
* Avoid from any HTML in the HTTP responses (including errors and exceptions)
* Any 'composer install' activity should be executed outside of image building and container execution
* Bring database configuration to a dedicated environment file
