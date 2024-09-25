#!/bin/bash

if [ "$1" == "fix" ]; then
  ./vendor/bin/phpcbf --standard=Drupal,DrupalPractice --extensions=php,module,inc,install,test,profile,theme,css,info,txt,md,yml docroot/sites/terceros/modules/custom/fsfb_terceros_form/src/Controller/uniqueEmail.php
else
  ./vendor/bin/phpcs --standard=Drupal,DrupalPractice --extensions=php,module,inc,install,test,profile,theme,css,info,txt,md,yml docroot/sites/terceros/modules/custom/fsfb_terceros_form/src/Controller/uniqueEmail.php
fi
