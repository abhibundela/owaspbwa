#!/bin/bash 
IP=`ifconfig  | grep 'inet addr:'| grep -v '127.0.0.1' | cut -d: -f2 | awk '{ print $1}'`
echo -e "\n\n\rWelcome to the OWASP Broken Web Apps VM" 
echo -e 
echo -e "\r!!! This VM has many serious security issues. We strongly recommend that you run" 
echo -e "\r    it only on the \"host only\" or \"NAT\" network in the VM settings !!!" 
echo -e "\r" 
echo -e "\rYou can access the web apps at http://$IP/" 
echo -e 
echo -e "\rYou can administer / configure this machine through the console here, by SSHing" 
echo -e "\rto $IP, via Samba at \\\\\\\\$IP\\, or via phpmyadmin at" 
echo -e "\rhttp://$IP/phpmyadmin." 
echo -e "\r" 
echo -en "\rIn all these cases, you can use username \"root\" and password \"owaspbwa\"."
