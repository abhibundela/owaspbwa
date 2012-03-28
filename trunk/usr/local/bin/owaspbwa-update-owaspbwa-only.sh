#!/bin/bash
echo "This script will attempt to update OWASP BWA files the OWASP BWA SVN"
echo "repository on Google Code."
echo
echo "This may lose local changes to files and databases and may cause problems"
echo "with applciations.  In some cases, however, this will allow you to receive"
echo "bug fixes and possibly enhancements without having to wait for a new release"
echo "of OWASP BWA."
echo
echo "Use this at your own risk and ensure that you have made a snapshot or copy"
echo "of the Virtual Machine"
echo
read -p "Press [Enter] to start updates or Control-C to abort..."
echo "---- Stopping services ----"
owaspbwa-services-stop.sh
echo "---- Updating from OWASP BWA SVN Repo ----"
svn update --accept theirs-full /owaspbwa/owaspbwa-svn
if [ "$?"-ne 0]; then echo "SVN Update Failed!"; exit 1; fi 
echo "---- Fixing file permissions and restarting services ----"
#when we update the scripts in /usr/local/bin, it may break the permissions
chmod +x /owaspbwa/owaspbwa-svn/usr/local/bin/*.sh
owaspbwa-fix-file-permissions.sh
