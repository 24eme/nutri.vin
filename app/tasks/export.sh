#! /bin/bash

_DIR=$(dirname "$0")
_DATE=$(date -Iseconds | sed -e 's/T//' -e 's/://g' -e 's/\+.*$//')

php "$_DIR/QRCodeExportCsv.php" > "/tmp/qrcodes_${_DATE}.csv"

cp "/tmp/qrcodes_${_DATE}.csv" "/tmp/qrcodes_latest.csv"
