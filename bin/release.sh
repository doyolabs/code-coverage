#!/usr/bin/env bash

#!/usr/bin/env bash

set -e

if (( "$#" != 1 ))
then
    echo "Tag has to be provided"

    exit 1
fi

BRANCH="master"
VERSION=$1

# Always prepend with "v"
if [[ $VERSION != v*  ]]
then
    VERSION="v$VERSION"
fi

for REMOTE in code-coverage-bridge behat-code-coverage phpspec-code-coverage
do
    echo ""
    echo ""
    echo "Releasing $REMOTE";

    TMP_DIR="/tmp/code-coverage-split"
    REMOTE_URL="git@github.com:doyolabs/$REMOTE.git"

    rm -rf $TMP_DIR;
    mkdir $TMP_DIR;

    (
        cd $TMP_DIR;

        git clone $REMOTE_URL .
        git checkout "$BRANCH";

        git tag -s $VERSION
        git push origin --tags
    )
done
