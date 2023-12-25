#!/bin/bash

# Navigate to the parent directory
cd ..

# Execute Symfony console commands
php bin/console app:addWilayas
php bin/console app:createStatesAndTypes
php bin/console app:launchProccess
php bin/console app:createTypesReal
php bin/console app:createTodoListsTemplates
php bin/console app:createBankAccounts
php bin/console app:createComnay
