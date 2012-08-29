#!/bin/bash

cd /opt/openlss

bin/scripts/build_core.sh
rm -rf /home/test/lss/*
rm -rf /home/test/lss/.lss*
lss -y -i web
