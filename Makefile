# Variable
SYMFONY_CONSOLE = symfony console
PHP = php

# Command
phpunit:
	$(SYMFONY_CONSOLE) d:d:c --env=test --if-not-exists
	$(SYMFONY_CONSOLE) d:s:u --env=test --force
	$(SYMFONY_CONSOLE) d:f:l --env=test --no-interaction
	$(PHP) bin/phpunit
	$(SYMFONY_CONSOLE) d:d:d --env=test --force
.PHONY: phpunit

sf-du:
	$(SYMFONY_CONSOLE) d:s:u --force
.PHONY: sf-du

sf-ds:
	$(SYMFONY_CONSOLE) d:d:c --if-not-exists
	$(SYMFONY_CONSOLE) d:s:u --force
	$(SYMFONY_CONSOLE) d:f:l --no-interaction
.PHONY: sf-ds