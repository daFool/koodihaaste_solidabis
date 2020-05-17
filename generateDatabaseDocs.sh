#!/bin/bash
source ./local.sh
robodoc --src $koodihaaste/src/sql --doc $koodihaaste/doc/database --multidoc --index --html --charset UTF-8 &>/dev/null
