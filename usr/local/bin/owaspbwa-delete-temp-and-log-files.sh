#!/bin/sh

# delete files in /tmp
rm -rf /tmp/*

# delete WebGoat builds
cd /owaspbwa/WebGoat-svn
mvn clean

# delete histories in home directories
rm /root/.bash_history
rm /root/.mysql_history
rm /root/.nano_history
rm -rf /root/.ssh
rm -rf /root/.subversion
rm -rf /root/.m2
rm /home/user/.bash_history
rm /home/user/.mysql_history
rm /home/user/.nano_history
rm -rf /home/user/.ssh
rm -rf /home/user/.subversion

# delete all files in /var/log, but not the directories
find /var/log -type f -exec rm {} \;

# delete downloaded debian packages
apt-get clean


