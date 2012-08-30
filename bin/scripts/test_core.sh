#!/bin/bash

cd /opt/openlss

lss --clear-cache
bin/scripts/build_core.sh
rm -rf /home/test/lss/*
rm -rf /home/test/lss/.lss*
lss --update
lss -y -i web
