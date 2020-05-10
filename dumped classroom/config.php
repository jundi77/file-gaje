<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = '10.199.13.80';
$CFG->dbname    = 'classroom';
$CFG->dbuser    = 'admin';
$CFG->dbpass    = 'Admin1234!';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => 3306,
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);

//$CFG->wwwroot   = 'http://10.199.13.81';
$CFG->wwwroot = 'https://classroom.its.ac.id';
$CFG->dataroot  = '/var/lmsdata';
$CFG->admin     = 'admin';

// Redis
$CFG->session_handler_class = '\core\session\redis';
$CFG->session_redis_host = '10.199.13.19';
$CFG->session_redis_port = 6379;  // Optional.
$CFG->session_redis_database = 0;  // Optional, default is db 0.
$CFG->session_redis_auth = 'Fnf9nWZ8+HvtE0KjW31QJdn9RLkkWtiNFvje8/b+sZ8k2FxjCrV/NzvO0zD9l1ljDF/L7T7fd4JCmXdU'; // Optional, default is don't set one.
$CFG->session_redis_prefix = 'redis_sess_'; // Optional, default is don't set one.
$CFG->session_redis_acquire_lock_timeout = 120;
$CFG->session_redis_lock_expire = 7200;
$CFG->session_redis_serializer_use_igbinary = true; // Optional, default is PHP builtin serializer.

$CFG->directorypermissions = 02777;

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
