PUPPETVERSIONS
==================

puppetversions tool is built for grabbing package version information from puppet using mcollective, perl, Slim 2.0 and datatablesphpmysql

Requirements:
  a) PHP 5.4, should work on 5.3+
  b) Mysql 5.5
  c) Apache 2.2
  d) perl and cron
  e) mcollective already working

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



API Samples:


    # ===> $ curl https://puppetversions.sec.domain.com/apiv1/  */
    {
        "httpstatus": "200",
        "error": "0",
        "data": {
            "response": "API"
        }
    }
    
    # ===> curl https://puppetversions.sec.domain.com/apiv1/echo/abcd  */
    {
        "httpstatus": "200",
        "error": "0",
        "data": {
            "response": "abcd"
        }
    }
    
    # ===> curl https://puppetversions.sec.domain.com/apiv1/denied
    # ===> curl https://puppetversions.sec.domain.com/apiv1/help
    # ===> curl -X POST -d 'Server=rrad3c50.domain.eu&Product=glibc' https://puppetversions.sec.domain.com/apiv1/puppetversions/exists/
    {
        "httpstatus": "200",
        "error": "1",
        "data": {
            "response": "exists: Entry exists",
            "rows": "1"
        }
    }

    
    # ===> curl -X POST -d 'Server=api2.domain.eu&Product=glibc&Version=2.12&Release=1.166.el6_7.7&Comment=AutomaticInseretTest' https://puppetversions.sec.domain.com/apiv1/puppetversions/insertORupdate/
    {
        "httpstatus": "200",
        "error": "0",
        "data": {
            "response": "insertORupdate: row inserted successfully",
            "Id": "28752"
        }
    }
    
    
    # ===> curl -X POST -d 'Server=web2.domain.eu&Product=glibc&Version=2.12&Release=1.166.el6_7.7&Comment=AutomaticInsert' https://puppetversions.sec.domain.com/apiv1/puppetversions/insert/
    {
        "httpstatus": "200",
        "error": "0",
        "data": {
            "response": "insert: row inserted successfully",
            "Id": "28753"
        }
    }
    
    
    # ===> curl https://puppetversions.sec.domain.com/apiv1/puppetversions/select/2
    {
        "httpstatus": "200",
        "error": "0",
        "data": {
            "response": {
                "Id": "2",
                "Server": "web.domain.com",
                "Product": "centos-release",
                "Version": "6",
                "Release": "7.el6.centos.12.3",
                "Date": "2016-07-25 01:54:00",
                "Comment": "AutomaticInsert"
            }
        }
    }
    
    
    # ===> curl https://puppetversions.sec.domain.com/apiv1/random_url_that_does_not_exist
    {
        "httpstatus": "404",
        "error": "1",
        "data": {
            "response": "The route you are requesting could not be found. Check \/help to ensure your request is spelled correctly."
        }
    }
    

