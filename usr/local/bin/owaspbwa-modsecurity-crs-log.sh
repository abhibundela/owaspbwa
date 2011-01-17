#!/bin/sh
rm /etc/apache2/apache2.conf
ln -s /etc/apache2/apache2-modsecurity-crs-log.conf /etc/apache2/apache2.conf
/etc/init.d/apache2 restart
