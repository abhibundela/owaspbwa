#!/bin/bash
echo "Compiling and deploying OWASP CSRFGuard Test Apps in /owaspbwa/wavsep-git/" 

#echo "Stoping Tomcat Service"
#/usr/sbin/service tomcat6 stop

cd /owaspbwa/wavsep-git
ant
cd - # return to previous directory

# fix file permissions and restart services
#echo ".: Fixing Tomcat Permissions :."
#chown -R tomcat6:tomcat6 /owaspbwa/owaspbwa-svn/var/lib/tomcat6/webapps
#/usr/sbin/service tomcat6 start
