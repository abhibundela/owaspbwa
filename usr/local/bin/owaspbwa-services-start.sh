#!/bin/sh
/usr/sbin/service mysql start
# start second instance of mysql on 3307 (used by SecurityShepherd)
/usr/bin/mysqld_multi start
/usr/sbin/service postgresql-8.4 start
/usr/sbin/service tomcat6 start
/usr/sbin/service apache2 start
