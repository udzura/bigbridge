#!/bin/bash
set -x

mkdir /run/sshd
mkdir /var/run/mysqld
chmod 777 /var/run/mysqld
chmod 700 /run/sshd

echo -n '[mysqld]\nskip-grant-tables' > /etc/mysql/conf.d/setup.cnf

# mysqld_safe &
# until echo -e "root\n" | nc localhost 3306 >/dev/null; do
#     sleep 0.1
# done

# #mysql_upgrade
# echo 'create database testapp' | mysql -uroot -proot
# echo 'create table messages (id int auto_increment not null primary key, username varchar(255), message text);' | mysql -uroot -proot testapp
# kill $(cat /var/run/mysqld/mysqld.pid)
# rm -f /etc/mysql/conf.d/setup.cnf
