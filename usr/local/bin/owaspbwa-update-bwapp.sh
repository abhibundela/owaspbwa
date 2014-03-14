#!/bin/bash
echo "This script will attempt to update the bWAPP application from" 
echo "its source code repository."
echo
echo "Use this at your own risk and ensure that you have made a snapshot or copy"
echo "of the Virtual Machine"
echo
read -p "Press [Enter] to start updates or Control-C to abort..."

echo "---- Stopping services ----"
owaspbwa-services-stop.sh

echo "---- Updating from bWAPP Git Repo ----"
cd /owaspbwa/bwapp-git/
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from bWAPP Git Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Fixing file permissions and restarting services ----"

chown -R www-data:www-data /owaspbwa/bwapp-git/

#the restart services
owaspbwa-services-start.sh

#reset the bWAPP database
wget -qO- http://127.0.0.1/bWAPP/reset.php?secret=bWAPP &> /dev/null
