<?php
/**
 * Change your database configuration here
 * DB_SERVER : your MySQL server name
 * DB_USER   : your MySQL database username
 * DB_PASS   : your MySQL database password
 * DB_NAME   : your MySQL database name
 */
defined('DB_SERVER') ? NULL : define("DB_SERVER", "localhost");
defined('DB_USER')   ? NULL : define("DB_USER"  , "root");
defined('DB_PASS')   ? NULL : define("DB_PASS"  , "root");
defined('DB_NAME')   ? NULL : define("DB_NAME"  , "parsclick");
/**
 * Change your database configuration here
 * PG_SERVER : your PostgreSQL server name
 * PG_PORT   : your PostgreSQL port number
 * PG_USER   : your PostgreSQL database username
 * PG_PASS   : your PostgreSQL database password
 * PG_NAME   : your PostgreSQL database name
 */
defined('PG_SERVER') ? NULL : define("PG_SERVER", "localhost");
defined('PG_PORT')   ? NULL : define("PG_PORT"  , "5432");
defined('PG_USER')   ? NULL : define("PG_USER"  , "hassan");
defined('PG_PASS')   ? NULL : define("PG_PASS"  , "azimi");
defined('PG_NAME')   ? NULL : define("PG_NAME"  , "Parsclick");
?>