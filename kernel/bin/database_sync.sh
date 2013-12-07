#!/bin/sh

cd ../include
php vendor/bin/doctrine orm:generate-entities        ../entity/ --update-entities
php vendor/bin/doctrine orm:generate-repositories    ../entity/ 
php vendor/bin/doctrine orm:schema-tool:d --force
php vendor/bin/doctrine orm:schema-tool:c
cd ../bin
