#!/bin/bash
echo "Compiling and deploying OWASP CSRFGuard Test Apps in /owaspbwa/owaspbwa-svn/misc-src/OWASP-CSRFGuard-TestApp-2.2-src/ and /owaspbwa/owaspbwa-svn/misc-src/OWASP-CSRFGuard-TestApp-2.2-Vulnerable-src/" 

cd /owaspbwa/owaspbwa-svn/misc-src/OWASP-CSRFGuard-TestApp-2.2-src
ant
cd - # return to previous directory

cd /owaspbwa/owaspbwa-svn/misc-src/OWASP-CSRFGuard-TestApp-2.2-Vulnerable-src
ant
cd - # return to previous directory
