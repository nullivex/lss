#!/bin/bash

cd /opt/openlss

# create folders
mkdir -p deb/usr/lss
mkdir -p deb/usr/lss/users

# copy files
cp -a tools deb/usr/lss
cp -a bin deb/usr/lss

# build package
dpkg -b deb lss_0.1.1_all.deb