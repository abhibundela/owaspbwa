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

# DVWA has moved to Git
#echo "---- Updating from DVWA SVN Repo ----"
#svn update /owaspbwa/dvwa-svn
#if [ "$?" -ne 0 ] ; then echo "SVN Update from DVWA SVN Repo Failed!"; exit 1; fi 

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

#echo "---- Updating from Mutillidae SVN Repo ----"
#svn update /owaspbwa/mutillidae-svn
#if [ "$?" -ne 0 ] ; then echo "SVN Update from Mutillidae SVN Repo Failed!"; exit 1; fi 

echo "---- Updating from OWASP Bricks SVN Repo ----"
svn update /owaspbwa/owaspbricks-svn
if [ "$?" -ne 0 ] ; then echo "SVN Update from OWASP Bricks SVN Repo Failed!"; exit 1; fi 

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

echo "---- Updating from webgoat.net Git Repo ----"
cd /owaspbwa/webgoat.net-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from webgoat.net Git Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from WackoPicko Git Repo ----"
cd /owaspbwa/WackoPicko-relative_urls-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from WackoPicko Git Repo Failed!"; exit 1; fi 
cd - # return to previous directory


# update the five Spiderlabs apps from Git (commented out since those apps have been moved to a new, unified Git Repo that we are not yet using)

# echo "---- Updating from CryptOMG Git Repo ----"
# cd /owaspbwa/CryptOMG-git
# git pull 
# if [ "$?" -ne 0 ] ; then echo "GIT Update from CryptOMG Git Repo Failed!"; exit 1; fi 
# cd - # return to previous directory

# echo "---- Updating from ShelLOL Git Repo ----"
# cd /owaspbwa/ShelLOL-git
# git pull 
# if [ "$?" -ne 0 ] ; then echo "GIT Update from ShelLOL Git Repo Failed!"; exit 1; fi 
# cd - # return to previous directory

# echo "---- Updating from SQLol Git Repo ----"
# cd /owaspbwa/SQLol-git
# git pull 
# if [ "$?" -ne 0 ] ; then echo "GIT Update from SQLol Git Repo Failed!"; exit 1; fi 
# cd - # return to previous directory

# echo "---- Updating from XMLmao Git Repo ----"
# cd /owaspbwa/XMLmao-git
# git pull 
# if [ "$?" -ne 0 ] ; then echo "GIT Update from XMLmao Git Repo Failed!"; exit 1; fi 
# cd - # return to previous directory

# echo "---- Updating from XSSmh Git Repo ----"
# cd /owaspbwa/XSSmh-git
# git pull 
# if [ "$?" -ne 0 ] ; then echo "GIT Update from XSSmh Git Repo Failed!"; exit 1; fi 
# cd - # return to previous directory

echo "---- Updating from Mutillidae Git Repo ----"
cd /owaspbwa/mutillidae-git/
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from Mutillidae Git Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from ModSecurity Core Rule Set Git Repo ----"
cd /owaspbwa/owasp-modsecurity-crs-git/
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from ModSecurity Core Rule Set Git Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from OWASP RailsGoat Git Repo ----"
cd /owaspbwa/railsgoat-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from OWASP RailsGoat Git Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from DVWA Git Repo ----"
cd /owaspbwa/dvwa-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from DVWA Git Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from Cyclone Transfers Git Repo ----"
cd /owaspbwa/bwa_cyclone_transfers-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from Cyclone Transfers Git Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from bWAPP Git Repo ----"
cd /owaspbwa/bwapp-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from bWAPP Git Repo Failed!"; exit 1; fi 
cd - # return to previous directory

echo "---- Updating from WAVSEP Git Repo ----"
cd /owaspbwa/wavsep-git
git pull 
if [ "$?" -ne 0 ] ; then echo "GIT Update from WAVSEP Git Repo Failed!"; exit 1; fi 
cd - # return to previous directory

#### TODO - call script to rebuild / redeploy WAVSEP

echo "---- Fixing file permissions and restarting services ----"
#when we update the scripts in /usr/local/bin, it may break the permissions
chmod +x /owaspbwa/owaspbwa-svn/usr/local/bin/*.sh

#the script below will restart services after fixing permissions
owaspbwa-fix-file-permissions.sh

# reset databases on various applications that have automated capability
owaspbwa-reset-databases.sh
