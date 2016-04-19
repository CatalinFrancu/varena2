#!/bin/bash

# Checks whether any special files have been modified by the last update.
# If so, prompts the user to take appropriate actions

check_run() {
	if echo "$changed_files" | grep --quiet "$1"; then
    messages+=("$2")
  fi
}

changed_files="$(git diff-tree -r --name-only --no-commit-id HEAD@{1} HEAD)"
messages=()

check_run varena.conf.sample "Please reconcile your varena.conf with varena.conf.sample"
check_run www/.htaccess.sample "Please reconcile your www/.htaccess with www/.htaccess.sample and/or Nginx"
check_run scripts/setup.sh "Please rerun scripts/setup.sh on your client"
check_run patches/ 'The database schema has changed. Please run "php scripts/migration.php"'

if [ ${#messages[@]} -gt 0 ]; then
  echo "**********************************************************************"
  echo "*                       Post-merge hook notice                       *"
  echo "**********************************************************************"
  echo ""
  echo "Some files have changed in the repository that require actions on your part:"
  for m in "${messages[@]}"; do
    echo "* $m"
  done
fi

