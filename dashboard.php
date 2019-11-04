<?php

$mouth = date('m');
$day = date('d');
$year = date('Y');

for($i = '0'; $i <= '6'; $i++) {
    $x = $i + '1';
    if ($i < '1') {
        $queryDay[$i] = "SELECT * FROM products WHERE post_date >= '" . mktime('0', '0', '0', $mouth, $day, $year) . "' AND post_date <= '" . mktime('0', '0', '0', $mouth, ($day + '1'), $year) . "'";
    }
    if ($i > '0' && $i < '2') {
        $queryDay[$i] = "SELECT * FROM products WHERE post_date <= '" . mktime('0', '0', '0', $mouth, $day, $year) . "' AND post_date >= '" . mktime('0', '0', '0', $mouth, ($day - '1'), $year) . "'";
    }
    if ($i > '1') {
        $queryDay[$i] = "SELECT * FROM products WHERE post_date <= '" . mktime('0', '0', '0', $mouth, ($day - $i), $year) . "' AND post_date >= '" . mktime('0', '0', '0', $mouth, ($day - $x), $year) . "'";
        $x++;
    }

    $resultDay[$i] = $mysqli->query($queryDay[$i]) OR trigger_error($mysqli->error . '[$queryDay[$i]]');
}

/*echo "<pre>";
print_r($resultDay['0']);
echo "</pre>";*/

/*echo date('Y-m-d', '1400950800');
echo "<br>";
echo date('Y-m-d', '1400864400');*/

$queryAll = "SELECT * FROM products";
$resultAll = $mysqli->query($queryAll) OR trigger_error($mysqli->error . "[$queryAll]");

/*while ($wpRow = $resultDay['6']->fetch_array(MYSQLI_ASSOC)) {
    var_dump(date("Y-m-d", $wpRow['post_date']));
}*/

/*$start = '1400112000';
$end = '1401494400';
while ($wpRow = $resultAll->fetch_array(MYSQLI_ASSOC)) {
    $days = mt_rand($start, $end);
    $queryEdit = "UPDATE products SET post_date = '" . $days . "' WHERE ID='" . $wpRow['ID'] . "'";
    $resultEdit = $mysqli->query($queryEdit) OR trigger_error($mysqli->error . "[$queryEdit]");
}*/

?>
                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-home color"></i> <?php echo $module['title']; ?></h2>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <div class="chart-container">
                                    <div id="dashboard-chart" class="chart-placeholder"></div>
                                </div>
                                <!-- end: CONTENT BODY -->
                            </div>

