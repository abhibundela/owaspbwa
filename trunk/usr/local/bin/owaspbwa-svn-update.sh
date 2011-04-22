#!/bin/sh
echo "---- Stopping services ----"
owaspbwa-services-stop.sh
echo "---- Updating from SVN ----"
svn update /owaspbwa/owaspbwa-svn
echo "---- Fixing file permissions and restarting services ----"
owaspbwa-fix-file-permissions.sh
