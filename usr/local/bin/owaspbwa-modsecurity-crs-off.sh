#!/bin/sh
echo "\nConfiguring Apache with the ModSecurity Core Rule Set disabled.  No logging or blocking of bad requests will occur.\n"
rm /etc/apache2/apache2.conf
ln -s /etc/apache2/apache2-modsecurity-crs-off.conf /etc/apache2/apache2.conf
/etc/init.d/apache2 restart
