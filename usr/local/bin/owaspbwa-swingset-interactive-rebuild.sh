#!/bin/bash
echo "Compiling and deploying OWASP ESAPI Java SwingSet Interactive in /owaspbwa/owasp-esapi-java-swingset-interactive-svn...." 
# build and deploy new version of OWASP ESAPI Java SwingSet Interactive
cd /owaspbwa/owasp-esapi-java-swingset-interactive-svn/SwingSet
ant
cd - # return to previous directory
