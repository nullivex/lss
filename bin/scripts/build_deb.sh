#!/bin/bash

cd /opt/openlss

# create folders
rm -r deb/usr/lss
mkdir -p deb/usr/lss

# copy files
cp -av tools deb/usr/lss
cp -av bin deb/usr/lss
rm -rv deb/usr/lss/bin/scripts

# build package
dpkg -b deb lss_0.0.1_all.deb