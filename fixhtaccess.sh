#!/bin/bash
base="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
ds="$base/src/frontend $base/src/backend"

for d in $ds; do 
    sed -i '1,3d' $d/.htaccess
    sed -i '1 i\'"Setenv koodihaasteIni $base/koodihaaste.ini" $d/.htaccess 
    sed -i '1 i\'"Setenv koodihaaste $base" $d/.htaccess 
    sed -i '1 i\'"Setenv mosBase $base/mosBase" $d/.htaccess 
done
