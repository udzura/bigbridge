#!/usr/bin/with-contenv bash
set -x

if [ x"$GITHUB_TOKEN" = x"" ]; then
  echo "!!! env var GITHUB_TOKEN should be set !!!"
  exit 127
fi

sed -i.old "s/!!!replacehere!!!/$GITHUB_TOKEN/" /etc/octopass.conf
rm -rf /var/cache/octopass/*

mkdir /run/sshd
mkdir /var/run/mysqld
chmod 777 /var/run/mysqld
chmod 700 /run/sshd

rm -rf /var/lib/mysql/*
install -d -o mysql -g mysql -m 750 /var/lib/mysql/{tmp,ibdata,iblog}
/usr/sbin/mysqld --initialize --user=mysql --ignore-db-dir=tmp --ignore-db-dir=ibdata --ignore-db-dir=iblog

rootpass=$(grep 'A temporary password is generated for root@localhost:' /var/log/mysql/* | awk '{print $NF}')
mysqld_safe &
until echo -e "root\n" | nc localhost 3306 >/dev/null; do
  sleep 0.1
done

echo "alter user root@localhost identified by 'enpit-pr0@0630'" | mysql -uroot -p$rootpass --connect-expired-password
rootpass='enpit-pr0@0630'

echo 'create database testapp;' | mysql -uroot -p$rootpass
echo "create user bigbridge@localhost identified by 'bigbridge0630';" | mysql -uroot -p$rootpass
echo "grant all on testapp.* to bigbridge@localhost identified by 'bigbridge0630';" | mysql -uroot -p$rootpass
echo 'create table messages (id int auto_increment not null primary key, username varchar(255), message text);' | mysql -uroot -p$rootpass testapp
kill $(cat /var/run/mysqld/mysqld.pid)
