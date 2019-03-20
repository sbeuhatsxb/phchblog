#!/bin/bash

cd "/docs/source"

if [ $# -eq 0 ]; then
    exec bash
fi

case "$1" in
    "init")
        # TODO
    ;;
    "livehtml") 
        make livehtml
    ;;
    "html") 
        make html
    ;;
    "singlehtml") 
        make singlehtml
    ;;
    "pdf") 
        make latexpdf
    ;;
    "help")
        make
    ;;
    *)
        echo "Default"
        set -x
        $@  
    ;;
esac
