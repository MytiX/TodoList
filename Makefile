# Variable
SYMFONY_CONSOLE = symfony console
PHP = php

# Command
run-phpunit:
	$(SYMFONY_CONSOLE) d:d:c --env=test --if-not-exists
	$(SYMFONY_CONSOLE) d:s:u --env=test --force
	$(SYMFONY_CONSOLE) d:f:l --env=test --no-interaction
	$(PHP) bin/phpunit
.PHONY: run-phpunit

sf-du:
	$(SYMFONY_CONSOLE) d:s:u --force
.PHONY: sf-du
