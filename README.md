puppetversions tool is built for grabbing package version information from puppet using mcollective, perl, Slim 2.0 and datatablesphpmysql


How it works/Integration:

1. configure mcollective on a secure host and run the script from cron
    - sudo cp puppet_versions.pl /localservices/sbin/ ; sudo chmod 755 /localservices/sbin/puppet_versions.pl
    - sudo cp hsec_puppet_versions /etc/cron.d/ ; sudo chmod 640 /etc/cron.d/hsec_puppet_versions


2. configure a database and table
    - see database/README


3. configure a web server to be able to receive data from the secure host and insert information into the database server
    - see webserver directory, copy the same structure to a virtual host

4. configure database/webserver files with your own information ( database server, username, password )
    - run 'egrep -Hira PASSWORD_HERE *' to see which files need modification

