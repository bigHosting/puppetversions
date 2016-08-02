### PuppetVersions
puppetversions tool is built for listing package version information from puppet using mcollective, perl, Slim 2.0 and datatablesphpmysql


### Requirements:
- PHP 5.4, should work on 5.3+ ( php-pdo, php-mysqlnd or php-mysql rpms )
- Mysql 5.5
- Apache 2.2
- perl and cron
- mcollective already working ( puppet already setup )


### Install :
- configure mcollective on a secure host and run the script from cron

    $ sudo cp puppet_versions.pl /localservices/sbin/ ; sudo chmod 755 /localservices/sbin/puppet_versions.pl
    $ sudo cp puppet_owners.pl /localservices/sbin/ ; sudo chmod 755 /localservices/sbin/puppet_owners.pl
    $ sudo cp hsec_puppet_versions /etc/cron.d/ ; sudo chmod 640 /etc/cron.d/hsec_puppet_versions

- configure a database and table
    # DATABASE puppetversions creation and table structure
    mysql> create database puppetversions
    mysql> use puppetversions;
    CREATE TABLE `main` (
      `Id` mediumint(15) unsigned NOT NULL AUTO_INCREMENT,
      `Server` varchar(250) NOT NULL,
      `Product` varchar(250) NOT NULL,
      `Version` varchar(250) NOT NULL,
      `Release` varchar(250) NOT NULL,
      `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      `Comment` varchar(250) NOT NULL DEFAULT 'Automatic insert',
      PRIMARY KEY (`Id`),
      UNIQUE KEY `Uniq` (`Server`,`Product`),
      KEY `Server` (`Server`),
      KEY `Product` (`Product`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    CREATE TABLE `owners` (
      `Id` mediumint(15) unsigned NOT NULL AUTO_INCREMENT,
      `Server` varchar(250) NOT NULL,
      `Owner` varchar(250) NOT NULL,
      `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`Id`),
      UNIQUE KEY `Uniq` (`Server`),
      KEY `Server` (`Server`),
      KEY `Owner` (`Owner`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1769 DEFAULT CHARSET=latin1;

    # Sample records
    mysql> insert into main VALUES ('1','web3c50.domain.com','openssl','1.0.1f','42.el6_7.4',NOW(),'Manual insert');
    mysql> insert into main VALUES ('2','web3c50.domain.com','glibc','2.12','1.166.el6_7.7',NOW(),'Manual insert');

    # GRANT ACCESS TO WEB SERVER. web server != database server
    mysql> use mysql;
    mysql> GRANT SELECT, INSERT, UPDATE ON puppetversions.* TO 'puppetversions'@'webserver.sec.domain.com' IDENTIFIED BY 'PASSWORD_HERE';
    mysql> FLUSH PRIVILEGES;
    mysql> SHOW GRANTS FOR 'puppetversions'@'webserver.sec.domain.com';

- configure a web server to be able to receive data from the secure host and insert information into the database server
    $ see webserver directory, copy the same structure to a virtual host

- configure database/webserver files with your own information ( database server, username, password )
    $ vim apiv1/lib/DB.php


- screenshot
    ![Datatable PHP SQL](http://i1087.photobucket.com/albums/j474/Zulfindra_Juliant/dat-php-sql_zps8df060a2.png)

### License :
- Opensource
