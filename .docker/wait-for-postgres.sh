#!/bin/ash
set -e

echo ">> Waiting for postgres to start"
WAIT=0
while ! nc -z database 5432; do
  sleep 1
  echo "   postgres not ready yet"
  WAIT=$(($WAIT + 1))
  if [ "$WAIT" -gt 20 ]; then
    echo "Error: Timeout when waiting for postgres socket"
    exit 1
  fi
done

echo ">> postgres socket available, resuming command execution"

"$@"
