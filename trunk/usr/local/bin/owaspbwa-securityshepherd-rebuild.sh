#!/bin/bash
echo "Compiling and deploying Security Shepherd in /owaspbwa/SecurityShepherd-git/SecurityShepherdCore/..." 

#echo "Stoping Tomcat Service"
#/usr/sbin/service tomcat6 stop

cd /owaspbwa/SecurityShepherd-git/SecurityShepherdCore
ant
mv /var/lib/tomcat6/webapps/ROOT.war /var/lib/tomcat6/webapps/shepherd.war 
cd - # return to previous directory

# fix file permissions and restart services
#echo ".: Fixing Tomcat Permissions :."
#chown -R tomcat6:tomcat6 /owaspbwa/owaspbwa-svn/var/lib/tomcat6/webapps
#/usr/sbin/service tomcat6 start

