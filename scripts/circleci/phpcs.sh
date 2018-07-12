#!/bin/bash

PHPCS_RESULT="$(phpcs --standard=Drupal --extensions=php,module,inc,install,test,profile,theme,css,info,txt,md web/modules/custom --ignore=web/themes/custom/*/pattern-lab --ignore=web/themes/custom/*/vendors --ignore=web/themes/custom/*/css -n)"

echo "${PHPCS_RESULT}"

STR_ERROR="ERROR"

if echo "$PHPCS_RESULT" | grep -q "$STR_ERROR"; then
  exit 1
fi
