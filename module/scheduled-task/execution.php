<?php

$ops = array('+', '-', '');
$randOps = array_rand($ops);
$operator = $ops[$randOps];
$time_and_window = time() + ($operator . $options['random_time']);
$queryTask = "SELECT * FROM scheduler WHERE fire_time <= '" . time() . "'";
$resultTask = $mysqli->query($queryTask) or trigger_error($mysqli->error . "[$queryTask]");

while ($taskRow = $resultTask->fetch_array(MYSQLI_ASSOC)) {
    $fire_time = $taskRow['fire_time'] + $taskRow['time_interval'];
    $queryUpdate = "UPDATE scheduler SET fire_time = '" . $fire_time . "', last_fired = '" . $taskRow['fire_time'] . "' WHERE id = '" . $taskRow['ID'] ."'";
    $resultUpdate = $mysqli->query($queryUpdate) or trigger_error($mysqli->error . "[$queryDelete]");
    runTask($taskRow['ID'], $taskRow['scriptpath'], $taskRow['blog_url'], $taskRow['type'], $taskRow['blogId']);
}

?>

