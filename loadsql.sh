#!/bin/bash
target="koodihaaste"
user="koodihaaste"
password="koodihaaste"
sql=src/sql

export PGPASSWORD=$password
for i in $sql/taulut/*.sql; do
    psql -h localhost -U $user -q $target -f $i
    if [ $? -ne 0 ]; then
        echo "$i:$?"
        exit 1
    fi
done
for i in $sql/funktiot/*.sql;do
    psql -h localhost -U $user -q $target -f $i
    if [ $? -ne 0 ]; then
        echo "$i:$?"        
        exit 1
    fi
done
