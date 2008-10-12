#!/bin/bash
sqlite db/database.db .dump > db/database.sql
mv db/database.db db/database.db.old
sqlite3 db/database.db < db/database.sql
