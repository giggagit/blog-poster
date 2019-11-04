<?php

if (isset($_GET['length'])) {
    $limit = $_GET['length'];
} else {
    $limit = '10';
}

if (isset($_GET['search'])) {
    $search = slashes($_GET['search']);
}

if (empty($_GET['page'])) {
    $_GET['page'] = '1';
}

$page = (int)$_GET['page'];
if (strlen($page) > '0' && !is_numeric($page)) {
    echo 'Data Error';
    exit;
}

$eu = (($page - '1') * $limit); 
$queryTask = "SELECT * FROM scheduler";
if (isset($_GET['search'])) {
    $search = slashes($_GET['search']);
    $queryTask .= " WHERE blog_url LIKE '%" . $search . "%'";
} else {
    $search = NULL;
}

$resultTotal = $mysqli->query($queryTask)->num_rows OR trigger_error($mysqli->error . "[$queryTask]");
$queryData = $queryTask . " LIMIT $eu, $limit";
$resultTask = $mysqli->query($queryData) OR trigger_error($mysqli->error . "[$queryData]");

?>

                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-calendar color"></i> <?php echo $module['title']; ?></h2>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <div class="content-body">
                                    <form name ="tool-form" action="" method="GET">
                                        <input type="hidden" name="module" value="scheduled-task" />
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label>
                                                    <select name="length" class="form-control" size="1" onchange="this.form.submit()">
                                                        <option value="10" <?php echo ($limit == '10') ? "selected" : "" ?>>10</option>
                                                        <option value="20" <?php echo ($limit == '20') ? "selected" : "" ?>>20</option>
                                                        <option value="30" <?php echo ($limit == '30') ? "selected" : "" ?>>30</option>
                                                        <option value="40" <?php echo ($limit == '40') ? "selected" : "" ?>>40</option>
                                                        <option value="50" <?php echo ($limit == '50') ? "selected" : "" ?>>50</option>
                                                        <option value="100" <?php echo ($limit == '100') ? "selected" : "" ?>>100</option>
                                                    </select>
                                                </label>
                                            </div>
                                            <div class="col-md-6 input-group">
                                                <div class="input-group">
                                                    <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo $search; ?>">
                                                    <span class="input-group-btn"><button type="submit" class="btn default">Go!</button></span>
                                                </div>
                                            </div>
                                        </div>
                                        <noscript><input type="submit" value="Submit"></noscript>
                                    </form>
                                    <form name="wordpress-form" action="" method="POST">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="col-md-4">Blog URL</th>
                                                    <th class="col-md-2">Time Interval</th>
                                                    <th class="col-md-3">Last Fired</th>
                                                    <th class="col-md-3">Fire Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($taskRow = $resultTask->fetch_array(MYSQLI_ASSOC)) { ?>
                                                <tr>
                                                    <td><?php echo shortString($taskRow['blog_url'],'25'); ?></td>
                                                    <td><?php echo $taskRow['time_interval'] . " s"; ?></td>
                                                    <td><?php echo strftime("%H:%M:%S on %b %d, %Y", $taskRow['last_fired']); ?></td>
                                                    <td><?php echo strftime("%H:%M:%S on %b %d, %Y", $taskRow['fire_time']); ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </form>
                                    <div class="text-center">
                                        <ul class="pagination">
                                            <?php echo pagination($resultTotal, $page, $limit, 'page'); ?>
                                        </ul>
                                    </div>
                                </div>
                                <!-- end: CONTENT BODY -->
                            </div>

