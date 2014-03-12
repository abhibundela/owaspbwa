#!/bin/bash
echo "This script will attempt to update the Mutillidae from" 
echo "its respective source code repository."
echo
echo "Use this at your own risk and ensure that you have made a snapshot or copy"
echo "of the Virtual Machine"
echo
read -p "Press [Enter] to start updates or Control-C to abort..."

echo "---- Stopping services ----"
owaspbwa-services-stop.sh

echo "---- Updating from Mutillidae GIT Repo ----"
cd /owaspbwa/mutillidae-git/
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from Mutillidae GIT Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Fixing file permissions and restarting services ----"

chown -R www-data:www-data /owaspbwa/mutillidae-git/

#the restart services
owaspbwa-services-start.sh

#reset the mutillidae database
wget -qO- http://127.0.0.1/mutillidae/set-up-database.php &> /dev/null
