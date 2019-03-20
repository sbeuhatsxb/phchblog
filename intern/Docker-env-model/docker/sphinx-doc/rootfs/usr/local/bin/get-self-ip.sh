#!/bin/bash
grep $HOSTNAME /etc/hosts |awk '{print $1}'
