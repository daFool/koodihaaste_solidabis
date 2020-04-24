#!/bin/bash
mosBase=$(pwd)/mosBase
target="koodihaaste"
user="koodihaaste"
password="koodihaaste"

if [[ ! -f $mosBase/sql/tables/taulu_pohjat.sql ]]; then
    echo "Did you forget to clone mosBase?"
    exit 1
fi

psql -l &>/dev/null
if [ $? -ne 0 ]; then
    echo "Have you installed and started Postgresql?"
    exit 2
fi

psql -l |grep $target
if [ $? -eq 0 ]; then
    echo "You already have a database called $target, refusing to proceed"
    exit 3
fi
users=$(psql postgres --tuples-only -q --field-separator='#' --no-align <<here 
\du
\q
here
)
if [[ $? -ne 0 || -z $users ]]; then
    echo "Something failed with user listing, refusing to proceed"
    exit 4
fi
echo $users|cut -f 1 -d '#'|grep $user
if [[ $? -eq 0 ]]; then
    echo "You already have user $user, refusing to proceed"
    exit 5
fi
createuser $user
if [ $? -ne 0 ]; then
    echo "User: $user creation failed, sorry"
    exit 6
fi
createdb $target -O $user
if [ $? -ne 0 ]; then
    echo "Database creation failed, sorry"
    exit 7
fi
psql postgres -q <<jere
    alter user $user with encrypted password '$password';
    \q
jere
if [ $? -ne 0 ]; then
    echo "Unable to set database user password, sorry";
    exit 8
fi
export PGPASSWORD=$password
for i in $mosBase/sql/tables/taulu_pohjat.sql $mosBase/sql/tables/taulu_log.sql; do
    psql -h localhost -q -U $user $target -f $i >& /dev/null
    if [ $? -ne 0 ]; then
        echo "Unable to execute $i"
        exit 9
    fi
done

echo "Database $target with user $user and password $password created"



