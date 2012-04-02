#!/bin/bash
echo "Compiling and deploying OWASP ESAPI Java SwingSet in /owaspbwa/owasp-esapi-java-swingset-svn...." 
# build and deploy new version of OWASP ESAPI Java SwingSet
cd /owaspbwa/owasp-esapi-java-swingset-svn
mvn tomcat:undeploy ; mvn compile ; mvn war:exploded tomcat:exploded
cd - # return to previous directory
