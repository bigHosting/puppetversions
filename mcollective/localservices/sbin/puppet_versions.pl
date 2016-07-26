#!/usr/bin/perl

#
# 2016.07.25 Security Guy
#
# DATABASE puppetversions
# mysql> create database puppetversions
# mysql> use puppetversions;
#CREATE TABLE `main` (
#  `Id` mediumint(15) unsigned NOT NULL AUTO_INCREMENT,
#  `Server` varchar(250) NOT NULL,
#  `Product` varchar(250) NOT NULL,
#  `Version` varchar(250) NOT NULL,
#  `Release` varchar(250) NOT NULL,
#  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
#  `Comment` varchar(250) NOT NULL DEFAULT 'Automatic insert',
#  PRIMARY KEY (`Id`),
#  UNIQUE KEY `Uniq` (`Server`,`Product`),
#  KEY `Server` (`Server`),
#  KEY `Product` (`Product`)
#) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# mysql> GRANT SELECT, INSERT, UPDATE ON puppetversions.* TO 'puppetversions'@'nvd.sec.domain.com' IDENTIFIED BY 'PASSWORD_HERE';
# mysql> FLUSH PRIVILEGES;
# mysql> SHOW GRANTS FOR 'puppetversions'@'nvd.sec.domain.com';


use warnings;
use strict;

use File::Temp qw/ tmpnam /;

###############################################
#####  define functions, see end of file  #####
###############################################

sub ltrim;             # left trim function
sub rtrim;             # right trim function
sub trim;              # left & right trim function

###########################
#####  General  vars  #####
###########################

# API URL
my $URL = "https://puppetversions.sec.domain.com/apiv1/puppetversions/insertORupdate/";

# what package version we need to pull?
my @packages = ('centos-release',
                'curl',
                'gcc',
                'glibc',
                'hkernel',
                'httpd',
                'mysql-server',
                'net-snmp',
                'ntp',
                'openssh-server',
                'openssl',
                'perl',
                'php',
                'proftpd',
                'sendmail',
                'sssd',
                'sudo'
);

if ( scalar ( @packages ) == 0 )
{
        print "[*]: $0: ERROR: At least one package needs to be defined\n";
        exit(1);
}

# loop through packages, run mcollective command and parse results
foreach my $package (@packages)
{
        # create temporary file
        my $filename = tmpnam();

        if ( -e $filename ) {
                unless ( unlink $filename ) {
                        print "[*]: $0: ERROR: Can't delete $filename: $!\n";
                        next;
                }
        }

        my $mco = sprintf ("mco rpc package status package=%s --dt 30 -t 30 --np  > %s", $package, $filename );
        print "[*] $0: INFO: $mco\n";
        system ($mco);


        # use perl open function to open the file (or die trying)
        open(FILE, $filename) or die "Could not read from $filename, program halting.";
        my $count = 1;
        my %hash  = ();
        while (my $line=<FILE>) {
                #next if $. <= 4; # skip first 4 lines  # commenting out since we run mco with '--np'

                chomp($line);
                $line = trim($line);
                #next if (length ($line) == 0);
                #next if ($line eq '');

                $hash{$count} = $line;
                $count++;
        }
        close(FILE);

        # remove file as we don;t need it any longer
        unlink($filename);

        # new hash w proper format by parsing existing hash
        my ( %server, $tmp_name, $tmp_product, $tmp_version, $tmp_release ) ;
        foreach my $num (sort { $a <=> $b } keys(%hash) )
        {
                # package is NOT installed
                if ( $hash{$num} =~ m/Arch:/ && $hash{$num} =~ m/nil/)
                {

                        $tmp_name     = $num - 1;
                        #$tmp_name     = trim($tmp_name);
                        $server{$hash{$tmp_name}} = "$package:NA:NA";
                }

                # package is installed
                if ( $hash{$num} =~ m/Arch:/ && $hash{$num} =~ m/x86_64/)
                {
                        $tmp_name     = $num - 1;
                        $tmp_product  = $num + 3;
                        $tmp_version  = $num + 7;
                        $tmp_release  = $num + 6;

                        # store info to %server hash because we want sorted output
                        #print $hash{$tmp_name} . $hash{$tmp_product} . $hash{$tmp_version} . "\n";
                        $hash{$tmp_product} =~ s/Name: //g;
                        $hash{$tmp_product} = trim ($hash{$tmp_product});

                        $hash{$tmp_version} =~ s/Version: //g;
                        $hash{$tmp_version} = trim ($hash{$tmp_version});

                        $hash{$tmp_release} =~ s/Release: //g;
                        $hash{$tmp_release} = trim ($hash{$tmp_release});

                        $server{$hash{$tmp_name}} = "$hash{$tmp_product}:$hash{$tmp_version}:$hash{$tmp_release}";
                }
        }

        # go through results
        foreach my $key (sort (keys(%server)))
        {

                my ( $pkg, $ver, $rel ) = split (/:/, $server{$key});

                my %data = (
                             Product => "$pkg",
                             Release => "$rel",
                             Version => "$ver",
                             Server  => "$key",
                             Comment => "AutomaticInsert"
                );

                my $uri = join ("&", map {join ("=", $ _, $data {$ _})} sort keys %data);

                # send to API
                my $cmd = sprintf ("/usr/bin/curl -X POST -d '%s' $URL",$uri);
                #print "$key \t\t$server{$key}\n";
                print "$cmd\n";
                system($cmd);
        } # end foreach

} # end package loop
######################
#####  END MAIN  #####
######################

sub ltrim {
        my $s = shift;
        $s =~ s/^\s+//;
        return $s;
};

sub rtrim {
        my $s = shift;
        $s =~ s/\s+$//;
        return $s;
};
sub trim  {
        my $s = shift;
        $s =~ s/^\s+|\s+$//g;
        return $s;
};

