#!/bin/bash
echo ".: Fixing Web Permissions :."
chown -R www-data:www-data /owaspbwa/owaspbwa-svn/var/www
echo ".: Fixing MySQL Permissions:."
chown -R mysql:mysql /owaspbwa/owaspbwa-svn/var/lib/mysql
echo ".: Fixing Tomcat Permissions :."
chown -R tomcat6:tomcat6 /owaspbwa/owaspbwa-svn/var/lib/tomcat6
