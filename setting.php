<?php

// Error Report
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Script Version
$version = '2.1.2';

// Database config
$db_host = 'localhost'; //  Database Host
$db_user = 'root';      //  Database user name
$db_password = '';      //  Database password for user
$db_database = 'test'; // db name

// Connect to database
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_database);
if ($mysqli->connect_errno) {
    echo 'Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
}

// Update Uptions
if (isset($_POST['options'])) {
    if ($_POST['options'] == 'update') {
        $getOptions = slashes($_POST);
        $getOptions['exclude_merchant'] = str_replace("\r\n", ', ', $_POST['exclude_merchant']);
        foreach ($getOptions as $name => $value) {
            $queryUpdate = "UPDATE options SET option_value = '" . $value . "' WHERE option_name = '$name'";
            $resultUpdate = $mysqli->query($queryUpdate) OR trigger_error($mysqli->error . "[$queryUpdate]");
        }

        $alertMsg = 'alert-success';
        $optionsMsg = '<span class="label label-success">Success</span> Update options';
    }
}

// Query options from database
$queryOptions = "SELECT * FROM options LIMIT 0, 20";
$resultOptions = $mysqli->query($queryOptions) OR trigger_error($mysqli->error . "[$queryOptions]");
while ($optionsRow = $resultOptions->fetch_array(MYSQLI_ASSOC)) {
    $options[$optionsRow['option_name']] = $optionsRow['option_value'];
}

// Time Zone
date_default_timezone_set($options['time_zone']);

?>