#!/usr/bin/perl

#
# Security Guy 2016.07.26
#

use strict;
use warnings;

use LWP::Simple;

binmode STDOUT, ":utf8";
use utf8;

use JSON;
use 5.010;

my $json = get("https://puppetversions.sec.domain.com/apiv1/puppetversions/products");
if( ! defined( $json ) ) {
        die( "[*] $0: ERROR: Can not fetch URL\n" );
}

#print "$json\n";

# 'read' json
my $decoded = decode_json($json);

# process results only if httpstatus is 200 and error is 0
if ( $decoded->{'httpstatus'} == 200 &&  $decoded->{'error'} == 0 )
{
        # products array
        my @products = @{ $decoded->{'data'} };

        # do we have at least one entry ?
        if ( scalar (@products) > 0 )
        {
                foreach my $product ( @products ) {
                        print $product . "\n";
                }
        }

}
