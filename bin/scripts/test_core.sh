#!/bin/bash

cd /opt/openlss

bin/scripts/build_core.sh
rm -rf /home/test/lss/*
rm -rf /home/test/lss/.pkgs*
lss -y -i web
