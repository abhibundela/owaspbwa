#!/bin/bash
echo "Compiling and deploying OWASP CSRFGuard Test Apps in /owaspbwa/owaspbwa-svn/misc-src/OWASP-CSRFGuard-TestApp-2.2-src/ and /owaspbwa/owaspbwa-svn/misc-src/OWASP-CSRFGuard-TestApp-2.2-Vulnerable-src/" 

#echo "Stoping Tomcat Service"
#/usr/sbin/service tomcat6 stop

cd /owaspbwa/owaspbwa-svn/misc-src/OWASP-CSRFGuard-TestApp-2.2-src
ant
cd - # return to previous directory

cd /owaspbwa/owaspbwa-svn/misc-src/OWASP-CSRFGuard-TestApp-2.2-Vulnerable-src
ant
cd - # return to previous directory

# fix file permissions and restart services
#echo ".: Fixing Tomcat Permissions :."
#chown -R tomcat6:tomcat6 /owaspbwa/owaspbwa-svn/var/lib/tomcat6/webapps
#/usr/sbin/service tomcat6 start
