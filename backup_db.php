<?php 

########################################
# CONFIGURATION VALUES                 #
########################################
$backup_path 	= "/home/<wmh_username>/<backup_folder>";  
$db_name		= "<database_name>";
$days_to_keep	= 7;
$time_zone      = "America/New_York";

########################################
# DO NOT EDIT ANYTHING BELOW THIS LINE #
########################################

########################################
# IMPORTANT NOTE                       #
########################################
/*

My Configuration File must exist in the users home directory
named ".my.cnf" with the DB Backup Username and Password
Below is an example of the contents of this file.

[mysqldump]
user=<username>
password=<password>

*/

date_default_timezone_set($time_zone);

$strFilename = $backup_path ."/" .$db_name ."-" .date("Ymd-Hi") .".sql.gz";
echo "Backing up " .$db_name ." to " .$strFilename .PHP_EOL;

if (!file_exists($backup_path)) {
	mkdir($backup_path);
}

if (file_exists($backup_path) && !is_dir($backup_path))  {
	die("Backup Path is not a directory.");
}
exec("mysqldump --defaults-extra-file=~/.my.cnf --no-tablespaces --opt " .$db_name ." | gzip > " .$strFilename);


$arrFiles = glob(cacheme_directory() . '*');
$intRetention = strtotime('-' .(int)$days_to_keep  .' day');
  
foreach ($arrFiles as $strFile) {
    if (is_file($strFile)) {
        if ($intRetention >= filemtime($strFile)) {
            echo "Removing old backup " .basename($strFile) .PHP_EOL;
            unlink($strFile);
        }
    }
}




