#!/bin/bash

program=$(basename $0)

if [ -z "$cmd" ]; then
    cmd="$program $@"
fi

. $(dirname $0)/.common

exec docker-compose exec --user=${ZOO_UID-1000}:${ZOO_GID-1000} ${COMPOSE_OPTS} zoo1 $cmd
