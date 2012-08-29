#!/bin/bash

cd /opt/openlss

wdir="core"

echo "Making sure all files are unix formatted"
find /opt/openlss/$wdir -type f -name "*.php" | xargs -I{} dos2unix {}

#refactor
echo "Refactoring..."
while read pkg
do
	bin/refactor --pkg $pkg --dir $wdir
done < bin/scripts/.core_pkgs
echo "  done"

#export
echo "Exporting..."
while read pkg
do
	bin/pkgexport --pkg $pkg --mirror /data/mirror
done < bin/scripts/.core_pkgs
echo "  done"

#update db
echo "Updating DB..."
bin/lss --build-db --export-db --mirror /data/mirror
echo "  done"

echo "Completed"