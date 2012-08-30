#!/bin/bash

cd /opt/openlss/docs

echo "Starting Man Page Update"
cp lss.man lss.1
rm lss.1.gz
gzip lss.1
cp lss.1.gz /usr/share/man/man1
mandb
echo "Man Page Updated!"