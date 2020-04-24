#!/bin/bash
target="koodihaaste"
user="koodihaaste"
password="koodihaaste"
sql=src/sql

export PGPASSWORD=$password
for i in $sql/taulut/edges.sql $sql/taulut/qset.sql; do
    psql -h localhost -U $user -q $target -f $i
done