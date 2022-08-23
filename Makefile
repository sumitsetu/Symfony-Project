#SHELL := /bin/bash

.PHONY: tests

tests:
	@echo "drop database of test"
	@symfony console doctrine:database:drop --force --env=test || true
	@echo "create database of test"
	@symfony console doctrine:database:create --env=test
	@echo "create database schema for test"
	@symfony console doctrine:schema:create --env=test
	@echo Tests
	symfony php bin/phpunit --testsuite User
