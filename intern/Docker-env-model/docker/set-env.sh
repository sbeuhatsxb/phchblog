#!/bin/bash

if [ "$_" = "$0" ]; then
    echo "You must source this file: '. docker/set-env.sh'"
else
    appDir=$PWD
    dockerBinDir=$appDir/docker/bin
    if [ ! -d $dockerBinDir ]; then
        echo "$dockerBinDir does not exists"
    else
        export PATH=$dockerBinDir:$PATH
    fi
fi
