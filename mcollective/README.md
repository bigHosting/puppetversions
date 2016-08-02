
# We run the following commands in a loop to fetch version/host information

$ mco rpc package status package=openssl --dt 30 -t 30 --np
$ mco find -T web --dt 30 -t 30 --np
