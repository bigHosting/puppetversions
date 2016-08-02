
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
) ENGINE=InnoDB AUTO_INCREMENT=1764 DEFAULT CHARSET=latin1;


--- root@db.sec.domain.com [mysql]> GRANT SELECT, INSERT, UPDATE ON puppetversions.* TO 'puppetversions'@'webserver.sec.domain.com' IDENTIFIED BY 'PASSORD_HERE';
--- root@db.sec.domain.com [mysql]> FLUSH PRIVILEGES;
--- root@db.sec.domain.com [mysql]> SHOW GRANTS FOR 'puppetversions'@'webserver.sec.domain.com';
---+----------------------------------------------------------------------------------------------------------------------------------+
---| Grants for puppetversions@nvd.sec.domain.com                                                                                   |
---+----------------------------------------------------------------------------------------------------------------------------------+
---| GRANT USAGE ON *.* TO 'puppetversions'@'webserver.sec.domain.com' IDENTIFIED BY PASSWORD '*41280*******************' |
---| GRANT SELECT, INSERT, UPDATE ON `puppetversions`.* TO 'puppetversions'@'webserver.sec.domain.com'                                    |
---+----------------------------------------------------------------------------------------------------------------------------------+

