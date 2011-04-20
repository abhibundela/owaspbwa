#!/bin/bash
echo ".: Fixing Web Permissions :."
chown -R www-data:www-data /owaspbwa/owaspbwa-svn/var/www
/usr/sbin/service apache2 restart
echo ".: Fixing MySQL Permissions:."
chown -R mysql:mysql /owaspbwa/owaspbwa-svn/var/lib/mysql
/usr/sbin/service mysql restart
echo ".: Fixing Tomcat Permissions :."
chown -R tomcat6:tomcat6 /owaspbwa/owaspbwa-svn/var/lib/tomcat6
/usr/sbin/service tomcat6 restart
