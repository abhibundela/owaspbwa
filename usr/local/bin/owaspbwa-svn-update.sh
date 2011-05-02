#!/bin/sh
echo "---- Stopping services ----"
owaspbwa-services-stop.sh
echo "---- Updating from SVN ----"
svn update --accept theirs-full /owaspbwa/owaspbwa-svn
echo "---- Fixing file permissions and restarting services ----"
#when we update this filee, it breaks the permmissions
chmod +x /owaspbwa/owaspbwa-svn/usr/local/bin/owaspbwa-fix-file-permissions.sh
chmod +x /owaspbwa/owaspbwa-svn/usr/local/bin/owaspbwa-services-stop.sh
chmod +x /owaspbwa/owaspbwa-svn/usr/local/bin/owaspbwa-services-start.sh
owaspbwa-fix-file-permissions.sh
