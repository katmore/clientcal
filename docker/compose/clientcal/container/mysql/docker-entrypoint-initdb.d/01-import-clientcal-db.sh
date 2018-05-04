#!/bin/bash

ME_NAME='01-import-clientcal-db.sh'

[ -n "$SCHEMASQL_DIR" ] || { 
   >&2 echo "$ME_NAME: missing SCHEMASQL_DIR env variable"
   exit 1 
}

[ -d "$SCHEMASQL_DIR" ] || {
   >&2 echo "$ME_NAME: invalid SCHEMASQL_DIR '$SCHEMASQL_DIR' is not a directory"
   exit 1
}

[ -z "$CLIENTCAL_DATABASE" ] && CLIENTCAL_DATABASE='clientcal'

LATEST_SCHEMA_VERSION="2.00"

SCHEMA_DUMP_SQL="$SCHEMASQL_DIR/$LATEST_SCHEMA_VERSION/schema-dump.sql"

[ -f "$SCHEMA_DUMP_SQL" ] || {
   >&2 echo "$ME_NAME: invalid SCHEMA_DUMP_SQL '$SCHEMA_DUMP_SQL' is not a file"
   exit 1
}

mysql $CLIENTCAL_DATABASE < $SCHEMA_DUMP_SQL || {
   >&2 echo "$ME_NAME: failed to import SCHEMA_DUMP_SQL '$SCHEMA_DUMP_SQL'"
   exit 1
}