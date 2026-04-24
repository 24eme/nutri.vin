#! /bin/bash

_DIR=$(dirname "$0")
_DATE=$(date -Iseconds | sed -e 's/T//' -e 's/://g' -e 's/\+.*$//')

php "$_DIR/QRCodeExportCsv.php" > "$_DIR/exports/qrcodes_${_DATE}.csv"

cp "$_DIR/exports/qrcodes_${_DATE}.csv" "$_DIR/exports/qrcodes_latest.csv"
