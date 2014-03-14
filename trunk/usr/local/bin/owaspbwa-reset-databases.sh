#!/bin/bash
echo "Reseting databases for applications that have automated capbility to do so."
echo "Currently: Mutillidae, bWAPP, DVWA, and OWASP Bricks"

#reset the mutillidae database
wget -qO- http://127.0.0.1/mutillidae/set-up-database.php &> /dev/null

#reset the bWAPP database
wget -qO- http://127.0.0.1/bWAPP/reset.php?secret=bWAPP &> /dev/null

#reset the OWASP Bricks database
wget -qO- http://127.0.0.1/owaspbricks/config/setup.php  &> /dev/null

# reset the DVWA database
wget -qO- http://127.0.0.1/dvwa/setup.php --post-data="create_db=Create+%2F+Reset+Database" &> /dev/null
