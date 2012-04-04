#!/bin/bash
echo "Compiling and deploying Yazd in /owaspbwa/owaspbwa-svn/misc-src/Yazd_1.0-src/...." 
cd /owaspbwa/owaspbwa-svn/misc-src/Yazd_1.0-src/build
export JAVA_HOME=/usr/lib/jvm/java-6-openjdk ; ./ant
cd - # return to previous directory
