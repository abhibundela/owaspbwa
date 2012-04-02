#!/bin/bash
echo "Compiling and deploying WebGoat in /owaspbwa/WebGoat-svn...." 
# build and deploy new version of WebGoat
cd /owaspbwa/WebGoat-svn
mvn tomcat:undeploy ; mvn compile ; mvn war:exploded tomcat:exploded
cd - # return to previous directory
