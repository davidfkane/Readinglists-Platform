<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/


$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = "10.5.0.94";
#$db['default']['hostname'] = "193.1.12.171";
#$db['default']['hostname'] = "10.5.96.15";
$db['default']['username'] = "dkane";
$db['default']['password'] = "chamerops";
#$db['default']['password'] = "";
$db['default']['database'] = "mylibrarydb";
//$db['default']['database'] = "signposts";
//$db['default']['database'] = "mylibrarydb_testbed";
$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";



$db['stats']['hostname'] = "10.5.0.94";
#$db['moodle']['hostname'] = "193.1.12.171";
#$db['moodle']['hostname'] = "10.5.96.15";
$db['stats']['username'] = "root";
$db['stats']['password'] = "marigold";
#$db['moodle']['password'] = "";
$db['stats']['database'] = "yellow";
//$db['moodle']['database'] = "signposts";
//$db['moodle']['database'] = "mylibrarydb_testbed";
$db['stats']['dbdriver'] = "mysql";
$db['stats']['dbprefix'] = "";
$db['stats']['pconnect'] = TRUE;
$db['stats']['db_debug'] = TRUE;
$db['stats']['cache_on'] = FALSE;
$db['stats']['cachedir'] = "";
$db['stats']['char_set'] = "utf8";
$db['stats']['dbcollat'] = "utf8_general_ci";

/*



$db['moodle']['hostname'] = "127.0.0.1";
#$db['moodle']['hostname'] = "193.1.12.171";
#$db['moodle']['hostname'] = "10.5.96.15";
$db['moodle']['username'] = "root";
$db['moodle']['password'] = "marigold";
#$db['moodle']['password'] = "";
$db['moodle']['database'] = "staff_moodle";
//$db['moodle']['database'] = "signposts";
//$db['moodle']['database'] = "mylibrarydb_testbed";
$db['moodle']['dbdriver'] = "mysql";
$db['moodle']['dbprefix'] = "";
$db['moodle']['pconnect'] = TRUE;
$db['moodle']['db_debug'] = TRUE;
$db['moodle']['cache_on'] = FALSE;
$db['moodle']['cachedir'] = "";
$db['moodle']['char_set'] = "utf8";
$db['moodle']['dbcollat'] = "utf8_general_ci";




$db['dk_moodle']['hostname'] = "10.5.96.15";
#$db['dk_moodle']['hostname'] = "193.1.12.171";
#$db['dk_moodle']['hostname'] = "10.5.96.15";
$db['dk_moodle']['username'] = "root";
$db['dk_moodle']['password'] = "marigold";
#$db['dk_moodle']['password'] = "";
$db['dk_moodle']['database'] = "dk_moodle";
//$db['dk_moodle']['database'] = "signposts";
//$db['dk_moodle']['database'] = "mylibrarydb_testbed";
$db['dk_moodle']['dbdriver'] = "mysql";
$db['dk_moodle']['dbprefix'] = "";
$db['dk_moodle']['pconnect'] = TRUE;
$db['dk_moodle']['db_debug'] = TRUE;
$db['dk_moodle']['cache_on'] = FALSE;
$db['dk_moodle']['cachedir'] = "";
$db['dk_moodle']['char_set'] = "utf8";
$db['dk_moodle']['dbcollat'] = "utf8_general_ci";




#$active_group = 'default';
#$active_record = TRUE;
#
#$db['default']['hostname'] = '10.5.96.14';
#$db['default']['username'] = 'root';
#$db['default']['password'] = 'marigold';
#$db['default']['database'] = 'mylibrarydb';
#$db['default']['dbdriver'] = 'mysql';
#$db['default']['dbprefix'] = '';
#$db['default']['pconnect'] = TRUE;
#$db['default']['db_debug'] = TRUE;
#$db['default']['cache_on'] = FALSE;
#$db['default']['cachedir'] = '';
#$db['default']['char_set'] = 'utf8';
#$db['default']['dbcollat'] = 'utf8_general_ci';
#$db['default']['swap_pre'] = '';
#$db['default']['autoinit'] = TRUE;
#$db['default']['stricton'] = FALSE;
*/

/* End of file database.php */
/* Location: ./application/config/database.php */



#echo '<pre>';
#  print_r($db['default']);
#  echo '</pre>';
#
#  echo 'Connecting to database: ' .$db['default']['database'];
#  $dbh=mysql_connect
#  (
#    $db['default']['hostname'],
#    $db['default']['username'],
#    $db['default']['password'])
#    or die('Cannot connect to the database because: ' . mysql_error());
#    mysql_select_db ($db['default']['database']);
#
 #   echo '<br />   Connected OK:'  ;
 #   die( 'file: ' .__FILE__ . ' Line: ' .__LINE__); 
