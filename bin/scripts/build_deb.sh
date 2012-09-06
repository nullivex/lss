#!/bin/bash

cd /opt/openlss

ver="0.1.0"
dir="lss-$ver"

# create folders
rm -r deb/usr/lss
mkdir -p deb/usr/lss

# copy files
cp -av tools deb/$dir/usr/lss
cp -av bin deb/$dir/usr/lss
rm -rv deb/$dir/usr/lss/bin/scripts

# deal with man file
cp docs/lss.man docs/lss.1
gzip -f docs/lss.1
mkdir -p deb/$dir/usr/share/man/man1
cp docs/lss.1.gz deb/$dir/usr/share/man/man1

# build package
dpkg -b "deb/$dir" "${dir}_all.deb"