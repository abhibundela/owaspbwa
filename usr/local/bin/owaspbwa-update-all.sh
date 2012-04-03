#!/bin/bash
echo "This script will attempt to update all OWASP BWA applications from" 
echo "their respective source code repositories."
echo
echo "In many cases, this will break the updated application due to database"
echo "or other changes.  It also may lose local changes to files and databases."
echo "In some cases, however, this will allow you to use an updated version of"
echo "applications without having to wait for a new release of OWASP BWA."
echo
echo "Use this at your own risk and ensure that you have made a snapshot or copy"
echo "of the Virtual Machine"
echo
read -p "Press [Enter] to start updates or Control-C to abort..."

echo "---- Stopping services ----"
owaspbwa-services-stop.sh

echo "---- Updating from OWASP BWA SVN Repo ----"
# Use --accept theirs-full particularly because database files may have been 
# altered locally and we are okay with overwriting those with what is in SVN.
svn update --accept theirs-full /owaspbwa/owaspbwa-svn
if [ "$?"-ne 0]; then echo "SVN Update from OWASP BWA SVN Repo Failed!"; exit 1; fi 

echo "---- Updating from DVWA SVN Repo ----"
svn update /owaspbwa/dvwa-svn
if [ "$?"-ne 0]; then echo "SVN Update from DVWA SVN Repo Failed!"; exit 1; fi 

echo "---- Updating from OWASP ZAP WAVE SVN Repo ----"
svn update /owaspbwa/owasp-zap-wave-svn
if [ "$?"-ne 0]; then echo "SVN Update from OWASP ZAP WAVE SVN Repo Failed!"; exit 1; fi 

echo "---- Updating from ModSecurity Core Rule Set SVN Repo ----"
svn update /owaspbwa/modsecurity-crs-svn
if [ "$?"-ne 0]; then echo "SVN Update from ModSecurity Core Rule Set SVN Repo Failed!"; exit 1; fi 

echo "---- Updating from BodgeIt SVN Repo ----"
svn update /owaspbwa/bodgeit-svn
if [ "$?"-ne 0]; then echo "SVN Update from BodgeIt SVN Repo Failed!"; exit 1; fi 

echo "---- Updating from OWASP WebGoat (Java) SVN Repo ----"
svn update /owaspbwa/WebGoat-svn
if [ "$?"-ne 0]; then echo "SVN Update from OWASP WebGoat (Java) SVN Repo Failed!"; exit 1; fi 
# build and deploy new version of WebGoat
echo "Compiling and deploying WebGoat in /owaspbwa/WebGoat-svn...." 
cd /owaspbwa/WebGoat-svn
mvn tomcat:undeploy ; mvn compile ; mvn war:exploded tomcat:exploded
cd - # return to previous directory

echo "---- Updating from OWASP ESAPI Java SwingSet SVN Repo ----"
svn update /owaspbwa/owasp-esapi-java-swingset-svn
echo "Compiling and deploying OWASP ESAPI Java SwingSet in /owaspbwa/owasp-esapi-java-swingset-svn...." 
# build and deploy new version of OWASP ESAPI Java SwingSet 
cd /owaspbwa/owasp-esapi-java-swingset-svn
mvn tomcat:undeploy ; mvn compile ; mvn war:exploded tomcat:exploded
cd - # return to previous directory

echo "---- Updating from OWASP ESAPI Java SwingSet Interactive SVN Repo ----"
svn update /owaspbwa/owasp-esapi-java-swingset-interactive-svn
echo "Compiling and deploying OWASP ESAPI Java SwingSet Interactive in /owaspbwa/owasp-esapi-java-swingset-interactive-svn...." 
# build and deploy new version of OWASP ESAPI Java SwingSet Interactive
cd /owaspbwa/owasp-esapi-java-swingset-interactive-svn/SwingSet
ant
cd - # return to previous directory

echo "---- Updating from webgoat.net GIT Repo ----"
cd /owaspbwa/webgoat.net-git
git pull 
if [ "$?"-ne 0]; then echo "GIT Update from webgoat.net GIT Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from WackoPicko GIT Repo ----"
cd /owaspbwa/WackoPicko-relative_urls-git
git pull 
if [ "$?"-ne 0]; then echo "GIT Update from WackoPicko GIT Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Fixing file permissions and restarting services ----"
#when we update the scripts in /usr/local/bin, it may break the permissions
chmod +x /owaspbwa/owaspbwa-svn/usr/local/bin/*.sh

#the script below will restart services after fixing permissions
owaspbwa-fix-file-permissions.sh
