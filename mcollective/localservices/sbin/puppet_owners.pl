#!/usr/bin/perl

#
# Security Guy 2016.07.29
#

use strict;
use warnings;

sub trim;              # left & right trim function

# mcollectives
my @owners = qw(
ca
coreapi
db
dev
dns
f2e
mail
puppetmasters
web
);

# go through each team
foreach my $team ( @owners )
{
        # run mcollective and grab hosts
        my $cmd = sprintf ("mco find -T %s --dt 30 -t 30 --np", $team);
        print "[*] $0: INFO: $cmd\n";

        # open command output
        open (CMD, "$cmd |");

        # loop through each line
        foreach my $line (<CMD>) {

                # match host/server
                if ($line =~ m/^[A-Za-z0-9-_\.]+$/) {
                        $line = trim($line);
                        # send API request to insert or update database
                        my $api = sprintf("curl -X POST -d 'Server=%s&Owner=%s' https://puppetversions.sec.domain.com/apiv1/puppetversions/insertowner/", $line, $team);
                        print "[*] $0: INFO: $api\n";
                        system($api);
                }
        }
        close CMD;
}


sub trim  {
        my $s = shift;
        $s =~ s/^\s+|\s+$|\t+|\n+//g;
        return $s;
};

