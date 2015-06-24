#!/bin/sh
/usr/sbin/service tomcat6 stop
/usr/sbin/service apache2 stop
# stop second instance of mysql on 3307 (used by SecurityShepherd)
/usr/bin/mysqld_multi start
/usr/sbin/service mysql stop
/usr/sbin/service postgresql-8.4 stop
