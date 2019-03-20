#!/bin/bash

if [ $# -lt 2 ]; then
    echo "Usage $0 <commandAndArgs> <logFile> [sleepTime]"
    exit 1
fi

cmd=$1
logFile=$2
sleepTime=${3-1}

while [ 1 ]
do
    echo "[INFO] $(date) starting $cmd" >> $logFile

    $cmd >> $logFile 2>&1

    if [ $? -ne 0 ]; then
        echo "[ERROR] $(date) $cmd finished with error!" >> $logFile
    else
        echo "[INFO] $(date) $cmd finished" >> $logFile
    fi

    # we sleep a little to avoid bouncing
    sleep $sleepTime
done
