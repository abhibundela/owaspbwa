#!/bin/sh
echo "\nConfiguring Apache with the ModSecurity Core Rule Set configured to log (but not block) bad requests.  Log messages appear in /var/log/apache2/error.log\n"
rm /etc/apache2/apache2.conf
ln -s /etc/apache2/apache2-modsecurity-crs-log.conf /etc/apache2/apache2.conf
/etc/init.d/apache2 restart
