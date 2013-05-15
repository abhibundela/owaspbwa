#!/bin/sh

# delete files in /tmp
rm -rf /tmp/*

# delete histories in home directories
rm /root/.bash_history
rm /root/.mysql_history
rm /root/.nano_history
rm -rf /root/.ssh
rm -rf /root/.subversion
rm /home/user/.bash_history
rm /home/user/.mysql_history
rm /home/user/.nano_history
rm -rf /home/user/.ssh
rm -rf /home/user/.subversion

# truncate some logs for Mutillidae
mysql -D mutillidae -umutillidae -pmutillidae -e 'TRUNCATE TABLE hitlog'
mysql -D mutillidae -umutillidae -pmutillidae -e 'TRUNCATE TABLE captured_data'
truncate -s 0 /var/www/mutillidae/captured-data.txt

# delete all files in /var/log, but not the directories
find /var/log -type f -exec rm {} \;

# delete downloaded debian packages
apt-get clean


