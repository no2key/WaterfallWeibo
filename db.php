<?php
/**
 * Simple example of extending the SQLite3 class and changing the __construct
 * parameters, then using the open method to initialize the DB.
 */
 $dbh = new PDO('sqlite:users.db');
 if ($dbh){
 echo 'OK';
 }else{
 echo 'Err';
 }
 
 foreach ($dbh->query('SELECT username, country FROM users;') as $row)
 {
 echo $row[0];
 }
 

?>