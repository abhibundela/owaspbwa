#!/bin/bash
echo "Reseting databases for applications that have automated capbility to do so."
echo "Currently: Mutillidae, bWAPP, DVWA, OWASP Bricks, MCIR (sqlol), and Security Shepherd"

#reset the mutillidae database
wget -qO- http://127.0.0.1/mutillidae/set-up-database.php &> /dev/null

#reset the bWAPP database
wget -qO- http://127.0.0.1/bWAPP/reset.php?secret=bWAPP &> /dev/null

#reset the OWASP Bricks database
wget -qO- http://127.0.0.1/owaspbricks/config/setup.php  &> /dev/null

#reset the DVWA database
wget -qO- http://127.0.0.1/dvwa/setup.php --post-data="create_db=Create+%2F+Reset+Database" &> /dev/null

#reset the MCIR sqlol database
wget -qO- http://127.0.0.1/MCIR/sqlol/resetbutton.php  &> /dev/null

#reset the security shepherd database
cat /owaspbwa/SecurityShepherd-git/SecurityShepherdCore/database/dropAndRecreateCoreDB.sql | mysql -h 127.0.0.1 --port=3307 --user="securityshepherd" --password="securityshepherd"
cat /owaspbwa/SecurityShepherd-git/SecurityShepherdCore/database/SHA*.sql | mysql -h 127.0.0.1 --port=3307 --user="securityshepherd" --password="securityshepherd" --database="core"
cat /owaspbwa/SecurityShepherd-git/SecurityShepherdCore/database/coreSchema.sql | mysql -h 127.0.0.1 --port=3307 --user="securityshepherd" --password="securityshepherd"
cat /owaspbwa/SecurityShepherd-git/SecurityShepherdCore/database/moduleSchemas.sql | mysql -h 127.0.0.1 --port=3307 --user="securityshepherd" --password="securityshepherd"



