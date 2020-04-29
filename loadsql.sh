#!/bin/bash
target="koodihaaste"
user="koodihaaste"
password="koodihaaste"
sql=src/sql

export PGPASSWORD=$password
for i in $sql/taulut/*.sql; do
    psql -h localhost -U $user -q $target -f $i
done
for i in $sql/funktiot/*.sql;do
   psql -h localhost -U $user -q $target -f $i
done
