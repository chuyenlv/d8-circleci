#!/bin/bash

cd scripts/circleci
source functions.sh

echo -e "\n${txtgrn}Pushing the master branch to Bitbucket Upstream ${txtrst}"
terminus_login_pantheon
create_backup

echo -e "\n${txtgrn}Pushing the master branch to Bitbucket Upstream ${txtrst}"
prepare_variables
prepare_pantheon_folder
rsync_repos

cd $HOME/pantheon
# Make sure connection is git.
terminus connection:set "${PANTHEON_SITE_NAME}.dev" git

git add -A --force .
git commit -m "Circle CI build $CIRCLE_BUILD_NUM by $GIT_USER" -m "$COMMIT_MESSAGE"
git push -u origin master --force
