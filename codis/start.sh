#!/bin/sh
set -e

$GOPATH/src/github.com/CodisLabs/codis/admin/codis-dashboard-admin.sh start
$GOPATH/src/github.com/CodisLabs/codis/admin/codis-proxy-admin.sh start
$GOPATH/src/github.com/CodisLabs/codis/admin/codis-server-admin.sh start
$GOPATH/src/github.com/CodisLabs/codis/admin/codis-fe-admin.sh start
