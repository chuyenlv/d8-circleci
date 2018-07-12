#!/bin/bash

prepare_variables() {
  BUILD_DIR=$(pwd)
  txtred='\033[0;91m' # Red
  txtgrn='\033[0;32m' # Green
  txtylw='\033[1;33m' # Yellow
  txtrst='\033[0m' # Text reset.

  COMMIT_MESSAGE="$(git log -1 --pretty=%B)"
  GIT_MESSAGE_TEXT="";
}

prepare_pantheon_folder() {
  cd $HOME

  # Prepare pantheon repo folder.
  if [ ! -d "$HOME/pantheon" ]; then
    git clone "$PANTHEON_GIT_URL" pantheon
  fi

  cd $HOME/pantheon
  git fetch
  git pull
}

rsync_repos() {
  # Remove all changeable files from pantheon repo
  echo -e "\n${txtylw}Prepare upstream repo for deploy site${txtrst}"
  cd $HOME/pantheon
  if [ -d "$HOME/pantheon/web" ]
  then
    # Remove it without folder sites.
    find web -maxdepth 1 ! -name web ! -name sites | xargs rm -rf
  fi

  rm -rf $HOME/pantheon/config
  rm -rf $HOME/pantheon/vendor
  rm -f $HOME/pantheon/pantheon.yml
  rm -f $HOME/pantheon/composer.json
  rm -f $HOME/pantheon/composer.lock

  mkdir -p vendor
  mkdir -p config

  rm -rf $CIRCLE_WORKING_DIRECTORY/web/sites/default/files

  rsync -ar $CIRCLE_WORKING_DIRECTORY/web/ $HOME/pantheon/web/
  rsync -ar $CIRCLE_WORKING_DIRECTORY/vendor/ $HOME/pantheon/vendor/
  rsync -ar $CIRCLE_WORKING_DIRECTORY/config/ $HOME/pantheon/config/

  rm -f $HOME/pantheon/web/sites/default/settings.local.php
  rm -f $HOME/pantheon/web/sites/default/services.local.yml

  cp $CIRCLE_WORKING_DIRECTORY/scripts/templates/.gitignore .
  cp $CIRCLE_WORKING_DIRECTORY/scripts/templates/pantheon.yml .
  cp $CIRCLE_WORKING_DIRECTORY/composer.* .
}

terminus_login_pantheon() {
  # Log into terminus.
  echo -e "\n${txtylw}Logging into Terminus ${txtrst}"
  terminus auth:login --machine-token=$PANTHEON_MACHINE_TOKEN
}

create_backup() {
  terminus backup:create "${PANTHEON_SITE_NAME}.dev"
}
