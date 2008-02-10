BEGIN TRANSACTION;
CREATE TEMPORARY TABLE temp(id integer primary key, user text, passwd text, admin integer);
INSERT INTO temp SELECT id, user, passwd, admin FROM tor_passwd;
DROP TABLE tor_passwd;
CREATE TABLE tor_passwd(id integer primary key, user text, passwd text, admin integer, dir text, force_dir integer);
INSERT INTO tor_passwd(id, user, passwd, admin) SELECT id, user, passwd, admin FROM temp;
DROP TABLE temp;
COMMIT;