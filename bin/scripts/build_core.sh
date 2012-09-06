#!/bin/bash

cd /opt/openlss

wdir="/opt/openlss/core"

echo "Making sure all files are unix formatted"
find $wdir -type f -name "*.php" | xargs -I{} dos2unix {}

#refactor
echo "Refactoring..."
while read pkg
do
	lss --refactor=$pkg --dir $wdir
done < bin/scripts/.core_pkgs
echo "  done"

#export
echo "Exporting..."
while read pkg
do
	lss --export=$pkg --mirror /data/mirror
done < bin/scripts/.core_pkgs
echo "  done"

#update db
echo "Updating DB..."
bin/lss --build-db --export-db --mirror /data/mirror
echo "  done"

echo "Completed"