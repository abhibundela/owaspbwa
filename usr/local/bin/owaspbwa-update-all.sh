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
if [ "$?" -ne 0 ] ; then echo "SVN Update from OWASP BWA SVN Repo Failed!"; exit 1; fi 

echo "---- Updating from DVWA SVN Repo ----"
svn update /owaspbwa/dvwa-svn
if [ "$?" -ne 0 ] ; then echo "SVN Update from DVWA SVN Repo Failed!"; exit 1; fi 

echo "---- Updating from OWASP ZAP WAVE SVN Repo ----"
svn update /owaspbwa/owasp-zap-wave-svn
if [ "$?" -ne 0 ] ; then echo "SVN Update from OWASP ZAP WAVE SVN Repo Failed!"; exit 1; fi 

# The ModSecurity Core Rule Set has moved to GitHub, so we no longer update it here
#echo "---- Updating from ModSecurity Core Rule Set SVN Repo ----"
#svn update /owaspbwa/modsecurity-crs-svn
#if [ "$?" -ne 0 ] ; then echo "SVN Update from ModSecurity Core Rule Set SVN Repo Failed!"; exit 1; fi 

echo "---- Updating from BodgeIt SVN Repo ----"
svn update /owaspbwa/bodgeit-svn
if [ "$?" -ne 0 ] ; then echo "SVN Update from BodgeIt SVN Repo Failed!"; exit 1; fi 

echo "---- Updating from WIVET SVN Repo ----"
svn update /owaspbwa/wivet-svn
if [ "$?" -ne 0 ] ; then echo "SVN Update from WIVET SVN Repo Failed!"; exit 1; fi 

echo "---- Updating from Mutillidae SVN Repo ----"
svn update /owaspbwa/mutillidae-svn
if [ "$?" -ne 0 ] ; then echo "SVN Update from Mutillidae SVN Repo Failed!"; exit 1; fi 

echo "---- Starting Tomcat for possible deployment of updates to WebGoat (Java) and the ESAPI SwingSet ----"
/usr/sbin/service tomcat6 start

echo "---- Updating from OWASP WebGoat (Java) SVN Repo ----"
svn update /owaspbwa/WebGoat-svn
if [ "$?" -ne 0 ] ; then echo "SVN Update from OWASP WebGoat (Java) SVN Repo Failed!"; exit 1; fi 
# build and deploy new version of WebGoat
echo "Compiling and deploying WebGoat in /owaspbwa/WebGoat-svn...." 
cd /owaspbwa/WebGoat-svn
mvn tomcat:undeploy ; mvn compile ; mvn war:exploded tomcat:exploded
cd - # return to previous directory

# The OWASP ESAPI Java SwingSet (non-Interactive) is deprecated so we no longer update it here

#echo "---- Updating from OWASP ESAPI Java SwingSet SVN Repo ----"
#svn update /owaspbwa/owasp-esapi-java-swingset-svn
#if [ "$?" -ne 0 ] ; then echo "SVN Update from OWASP ESAPI Java SwingSet SVN Repo Failed!"; exit 1; fi 
#echo "Compiling and deploying OWASP ESAPI Java SwingSet in /owaspbwa/owasp-esapi-java-swingset-svn...." 
# build and deploy new version of OWASP ESAPI Java SwingSet 
#cd /owaspbwa/owasp-esapi-java-swingset-svn
#mvn tomcat:undeploy ; mvn compile ; mvn war:exploded tomcat:exploded
#cd - # return to previous directory

echo "---- Stopping Tomcat ----"
/usr/sbin/service tomcat6 stop

echo "---- Updating from OWASP ESAPI Java SwingSet Interactive SVN Repo ----"
svn update /owaspbwa/owasp-esapi-java-swingset-interactive-svn
if [ "$?" -ne 0 ] ; then echo "SVN Update from OWASP ESAPI Java SwingSet Interactive SVN Repo Failed!"; exit 1; fi 
echo "Compiling and deploying OWASP ESAPI Java SwingSet Interactive in /owaspbwa/owasp-esapi-java-swingset-interactive-svn...." 
# build and deploy new version of OWASP ESAPI Java SwingSet Interactive
cd /owaspbwa/owasp-esapi-java-swingset-interactive-svn/SwingSet
ant
cd - # return to previous directory

echo "---- Updating from webgoat.net GIT Repo ----"
cd /owaspbwa/webgoat.net-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from webgoat.net GIT Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from WackoPicko GIT Repo ----"
cd /owaspbwa/WackoPicko-relative_urls-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from WackoPicko GIT Repo Failed!"; exit 1; fi 
cd - # return to previous directory


# update the five Spiderlabs apps from Git

echo "---- Updating from CryptOMG GIT Repo ----"
cd /owaspbwa/CryptOMG-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from CryptOMG GIT Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from ShelLOL GIT Repo ----"
cd /owaspbwa/ShelLOL-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from ShelLOL GIT Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from SQLol GIT Repo ----"
cd /owaspbwa/SQLol-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from SQLol GIT Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from XMLmao GIT Repo ----"
cd /owaspbwa/XMLmao-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from XMLmao GIT Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from XSSmh GIT Repo ----"
cd /owaspbwa/XSSmh-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from XSSmh GIT Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from ModSecurity Core Rule Set GIT Repo ----"
cd /owaspbwa/owasp-modsecurity-crs-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from ModSecurity Core Rule Set GIT Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Fixing file permissions and restarting services ----"
#when we update the scripts in /usr/local/bin, it may break the permissions
chmod +x /owaspbwa/owaspbwa-svn/usr/local/bin/*.sh

#the script below will restart services after fixing permissions
owaspbwa-fix-file-permissions.sh
