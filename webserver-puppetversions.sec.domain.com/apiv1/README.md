

PUPPETVERSIONS API based on PHP, MySQL, Slim 2 API, Monolog & SimplPDO codecanyon.net PDO helper class
==========================

* Puppetversions supports regular browser requests and JSON input. Output is always JSON formatted response.
* Official documentation available [here](https://github.com/bigHosting/puppetversions)



General API SAMPLES
==========================

### print API in json format
    regular $ curl https://puppetversions.sec.domain.com/apiv1/
    json    $ curl https://puppetversions.sec.domain.com/apiv1/ -H "Content-Type: application/json"

    {
        "httpstatus": "200",
        "error": "0",
        "data": {
            "response": "API"
        }
    }



### sample of denied message w 403 Apache status
    regular $ curl https://puppetversions.sec.domain.com/apiv1/denied
    json    $ curl https://puppetversions.sec.domain.com/apiv1/denied -H "Content-Type: application/json"

    {
        "httpstatus": "403",
        "error": "1",
        "data": {
            "response": "denied"
        }
    }



### Print this help file
-    regular $ curl https://puppetversions.sec.domain.com/apiv1/help


PUPPETVERSIONS API SAMPLES
=============================

### 1. OSs - list all servers and associated Os based on centos-release package
    regular $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/OSs
    json    $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/OSs -H "Content-Type: application/json"

    {
        "httpstatus": "200",
        "error": "0",
        "data": [
            {
                "Id": "1",
                "Server": "web1c50.domain.com",
                "OS": "6.7"
            },
            {
                "Id": "2",
                "Server": "web2c50.domain.com",
                "OS": "6.8"
            }
        ]
    }



### 2. products - list all products from database
    regular $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/products
    json    $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/products -H "Content-Type: application/json"

    {
        "httpstatus": "200",
        "error": "0",
        "data": [
            "bash",
            "centos-release",
            "curl",
            "gcc",
            "glibc",
            "httpd",
            "MySQL-server",
            "net-snmp",
            "ntp",
            "openssh-server",
            "openssl",
            "perl",
            "php",
            "proftpd",
            "sendmail",
            "sendmail-custom",
            "sssd",
            "sudo"
        ]
    }



### 3. versions - list all versions from database
    regular $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/versions
    json    $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/versions -H "Content-Type: application/json"

    {
        "httpstatus": "200",
        "error": "0",
        "data": [
            "1.0.1e",
            "5.5.50",
            "5.6.31",
            "7.36.0",
            "8.14.9",
        ]
    }




### 4. releases - list all releases from database
    regular $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/releases
    json    $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/releases -H "Content-Type: application/json"

    {
        "httpstatus": "200",
        "error": "0",
        "data": [
            "1.192.el6",
            "1.linux2.6",
            "1.linux_glibc2.5",
            "111.el6",
            "112.el6_7",
            "114.el6_7",
            "119.el6_1.1",
            "129.el6_5.4",
            "52.el6",
            "94.el6",
            "NA"
        ]
    }



### 5. servers - list all servers from database
    regular $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/servers
    json    $ curl https://puppetversions.sec.domain.com/apiv1/puppetversions/servers -H "Content-Type: application/json"
    
    {
        "httpstatus": "200",
        "error": "0",
        "data": [
            "web1c50.domain.com",
            "web2c50.domain.com",
            "web3c50.domain.com",
            "web4c50.domain.com",
            "yum1exp.domain.com"
        ]
    }



### 6. exists - verify if a Version is associated with a server
    regular $ curl -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/exists/ -d 'Server=web3c50.domain.com&Product=glibc'
    json    $ curl -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/exists/ -d '{"Server":"web3c50.domain.com", "Product":"glibc"}' -H "Content-Type: application/json"
    
    {
        "httpstatus": "200",
        "error": "1",
        "data": {
            "response": "exists: Entry exists",
            "rows": "1"
        }
    }



### 7. insertowner - insert team owner of a server
    regular $ curl -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/insertowner/ -d 'Server=api3c5.domain.com&Owner=db'
    json    $ curl -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/insertowner/ -d '{"Server":"apic5.domain.com", "Owner":"db"}' -H "Content-Type: application/json"
    
    {
        "httpstatus": "200",
        "error": "0",
        "data": {
            "response": "insertowner: row updated successfully"
        }
    }



### 8. insertORupdate
    regular $ curl -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/insertORupdate/ -d "Server=web1c45.domain.com&Product=glibc&Version=2.12&Release=1.166.el6_7.7&Comment=AutomaticInsert"
    json    $ curl -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/insertORupdate/ -d '{"Server":"web1c45.domain.com", "Product":"glibc", "Version":"2.12", "Release":"1.166.el6_7.7", "Comment":"AutomaticInsert"}'  -H "Content-Type: application/json"
    
    {
        "httpstatus": "200",
        "error": "0",
        "data": {
            "response": "insertORupdate: row updated successfully",
            "Id": "32500"
        }
    }



### 9. product - get a list of servers sharing a common Product version
    regular $ curl -s -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/product -d 'Product=glibc'
    json    $ curl -s -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/product -d '{"Product":"glibc"}' -H "Content-Type: application/json"
    
    {
        "httpstatus": "200",
        "error": "0",
        "data": [
            {
                "Id": "5394",
                "Server": "web4c50.domain.com",
                "Product": "glibc",
                "Version": "2.12",
                "Release": "1.166.el6_7.7",
                "Date": "2016-08-01 11:28:59",
                "Comment": "AutomaticInsert"
            },
            {
                "Id": "32500",
                "Server": "web1c45.domain.com",
                "Product": "glibc",
                "Version": "2.12",
                "Release": "1.166.el6_7.7",
                "Date": "2016-08-01 05:09:03",
                "Comment": "AutomaticInsert"
            }
        ]
    }



### 10. release - print a list of servers with a common release string
    regular $ curl -s -X GET  https://puppetversions.sec.domain.com/apiv1/puppetversions/release/1.166.el6_7.7
    
    regular $ curl -s -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/release -d 'Release=1.166.el6_7.7'
    json    $ curl -s -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/release -d '{"Release":"1.166.el6_7.7"}' -H "Content-Type: application/json"
    
    {
        "httpstatus": "200",
        "error": "0",
        "data": [
            {
                "Id": "5394",
                "Server": "web4c50.domain.com",
                "Product": "glibc",
                "Version": "2.12",
                "Release": "1.166.el6_7.7",
                "Date": "2016-08-01 11:28:59",
                "Comment": "AutomaticInsert"
            },
            {
                "Id": "32500",
                "Server": "web1c45.domain.com",
                "Product": "glibc",
                "Version": "2.12",
                "Release": "1.166.el6_7.7",
                "Date": "2016-08-01 05:09:03",
                "Comment": "AutomaticInsert"
            }
        ]
    }



### 11. search - by Product & Version
    regular $ curl -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/search -d "Product=glibc&Version=2.12"
    json    $ curl -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/search -d '{"Product":"glibc", "Version":"2.12"}' -H "Content-Type: application/json"
    
    {
        "httpstatus": "200",
        "error": "0",
        "data": [
            {
                "Id": "5394",
                "Server": "web4c50.domain.com",
                "Product": "glibc",
                "Version": "2.12",
                "Release": "1.166.el6_7.7",
                "Date": "2016-08-01 11:28:59",
                "Comment": "AutomaticInsert"
            },
            {
                "Id": "32500",
                "Server": "web1c45.domain.com",
                "Product": "glibc",
                "Version": "2.12",
                "Release": "1.166.el6_7.7",
                "Date": "2016-08-01 05:09:03",
                "Comment": "AutomaticInsert"
            }
        ]
    }



### 12. select - select a row Id
    regular $ curl -s -X GET  https://puppetversions.sec.domain.com/apiv1/puppetversions/select/32500
    
    regular $ curl -s -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/select -d "Id=32500"
    json    $ curl -s -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/select -d '{"Id":"32500"}' -H "Content-Type: application/json"
    
    {
        "httpstatus": "200",
        "error": "0",
        "data": {
            "response": {
                "Id": "32500",
                "Server": "web1c45.domain.com",
                "Product": "glibc",
                "Version": "2.12",
                "Release": "1.166.el6_7.7",
                "Date": "2016-08-01 05:09:03",
                "Comment": "AutomaticInsert"
            }
        }
    }



### 13. server - print all db info about a server
    regular $ curl -s -X GET  https://puppetversions.sec.domain.com/apiv1/puppetversions/server/web1c45.domain.com
    
    regular $ curl -s -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/server/ -d 'Server=web1c45.domain.com'
    json    $ curl -s -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/server/ -d '{"Server":"web1c45.domain.com"}' -H "Content-Type: application/json"
    
    {
        "httpstatus": "200",
        "error": "0",
        "data": [
            {
                "Id": "32500",
                "Server": "web1c45.domain.com",
                "Product": "glibc",
                "Version": "2.12",
                "Release": "1.166.el6_7.7",
                "Date": "2016-08-01 05:09:03",
                "Comment": "AutomaticInsert"
            }
        ]
    }


### 14. version - print sserver list associated with a certain version
    regular $ curl -s -X GET  https://puppetversions.sec.domain.com/apiv1/puppetversions/version/5.0.2.7
    
    regular $ curl -s -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/version/ -d 'Version=5.0.2.7'
    json    $ curl -s -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/version/ -d '{"Version":"5.0.2.7"}' -H "Content-Type: application/json"
    
    {
        "httpstatus": "200",
        "error": "0",
        "data": [
            {
                "Id": "7186",
                "Server": "web1c45.domain.com",
                "Product": "hkernel",
                "Version": "5.0.2.7",
                "Release": "1",
                "Date": "2016-08-01 11:34:30",
                "Comment": "AutomaticInsert"
            },
            {
                "Id": "32487",
                "Server": "web100.domain.com",
                "Product": "hkernel",
                "Version": "5.0.2.7",
                "Release": "1",
                "Date": "2016-08-01 11:37:26",
                "Comment": "AutomaticInsert"
            }
        ]
    }


### 15. insert - insert a new row
    regular $ curl -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/insert/ -d "Server=api4.domain.com&Product=glibc&Version=2.12&Release=1.166.el6_7.7&Comment=AutomaticInsert"
    json    $ curl -X POST https://puppetversions.sec.domain.com/apiv1/puppetversions/insert/ -d '{"Server":"api4.domain.com", "Product":"glibc", "Version":"2.12", "Release":"1.166.el6_7.7", "Comment":"AutomaticInsert"}' -H "Content-Type: application/json" 
    
    {
        "httpstatus": "200",
        "error": "0",
        "data": {
            "response": "insert: row inserted successfully",
            "Id": "32501"
        }
    }
    

### (c) Security Guy 2016.08.01
