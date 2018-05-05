#!/bin/bash
# Imports the latest ClientCal SQL dump according to 'schema-sql/db-schema.json'.
#
# @author D. Bird <doug@katmore.com>
#

# script localization
ME_NAME='01-import-clientcal-db.sh'

# mysql console command
MYSQL_CMD=${mysql[@]}
[ -n "$MYSQL_CMD" ] || MYSQL_CMD=mysql

# path to schema-sql dir
[ -n "$SCHEMASQL_DIR" ] || SCHEMASQL_DIR=/var/lib/schema-sql

# clientcal database name
[ -n "$CLIENTCAL_DATABASE" ] || CLIENTCAL_DATABASE='clientcal'

# path to db-schema.json
DB_SCHEMA_PATH=$SCHEMASQL_DIR/db-schema.json

# enforce sanity of mysql console command
echo 'SELECT version() as version;' | $MYSQL_CMD > /dev/null || {
   >&2 echo "$ME_NAME: mysql command '$MYSQL_CMD' failed with exit status $?"
   exit 1
}

# enforce sanity of jq command
hash jq || {
   >&2 echo "$ME_NAME: 'jq' command is missing or inaccessible"
   exit 1
}

# enforce sanity of db-schema.json file
[ -f "$DB_SCHEMA_PATH" ] || {
   >&2 echo "$ME_NAME: db-schema.json file '$DB_SCHEMA_PATH' not found"
   exit 1
}

# get 'latestDump' value from /var/lib/schema-sql/db-schema.json
LATEST_DUMP=$(dirname $DB_SCHEMA_PATH)/$(jq -e -r ".latestDump" $DB_SCHEMA_PATH) || {
   CMD_STATUS=$?
   if [ "$CMD_STATUS" = "1" ]; then
      >&2 echo "$ME_NAME: invalid db-schema.json file '$DB_SCHEMA_PATH', missing 'latestDump' value"
   else
      >&2 echo "$ME_NAME: failed to get the latest SQL dump file; 'jq' failed with exit status $?"
   fi
   exit 1
}

# enforce sanity of latest SQL dump file
[ -f "$LATEST_DUMP" ] || {
   >&2 echo "$ME_NAME: latest SQL dump file '$LATEST_DUMP' not found"
   exit 1
}

# import latest SQL dump file with mysql console
$MYSQL_CMD $CLIENTCAL_DATABASE < $LATEST_DUMP || {
   >&2 echo "$ME_NAME: failed to import LATEST_DUMP '$LATEST_DUMP', 'mysql' failed with exit status $?"
   exit 1
}
echo "($ME_NAME) imported latest SQL dump file: $LATEST_DUMP"







