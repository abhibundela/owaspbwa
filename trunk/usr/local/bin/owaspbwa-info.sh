#!/bin/sh -e
IP=`ifconfig  | grep 'inet addr:'| grep -v '127.0.0.1' | cut -d: -f2 | awk '{ print $1}'`
echo
echo Welcome to the OWASP Broken Web Apps VM
echo 
echo "!!! This VM has many serious security issues, we strongly recommend that you run"
echo "    it only in \"host only\" or \"NAT\" network in the virtual machine settings !!!"
echo
echo "You can access the web apps at http://$IP/" 
echo
echo "You can administer / configure this machine through the console here, by SSHing"
echo "to $IP, via Samba at \\\\\\\\$IP\\, or via phpmyadmin at"
echo "http://$IP/phpmyadmin."
echo
echo "In all these cases, you can use username \"root\" and password \"owaspbwa\"."
echo
