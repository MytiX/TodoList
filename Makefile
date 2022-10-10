# Variable
SYMFONY_CONSOLE = symfony console
PHP = php

# Command
phpunit: create-test-env
	$(PHP) bin/phpunit

create-test-env:
	$(SYMFONY_CONSOLE) d:d:d --env=test --force --if-exists
	$(SYMFONY_CONSOLE) d:d:c --env=test --if-not-exists
	$(SYMFONY_CONSOLE) d:s:u --env=test --force
	$(SYMFONY_CONSOLE) d:f:l --env=test --no-interaction

sf-du:
	$(SYMFONY_CONSOLE) d:s:u --force

sf-ds:
	$(SYMFONY_CONSOLE) d:d:c --if-not-exists
	$(SYMFONY_CONSOLE) d:s:u --force
	$(SYMFONY_CONSOLE) d:f:l --no-interaction

.PHONY: phpunit create-test-env sf-du sf-ds