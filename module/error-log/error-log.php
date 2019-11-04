<?php

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'delete-blog') {
        $blogID = slashes($_POST['blogID']);
        $blog = explode(', ', $blogID);
        $queryDelWP = "DELETE FROM " . $blog['1'] . " WHERE ID = '" . $blog['0'] . "'";
        $resultDelWP = $mysqli->query($queryDelWP) OR trigger_error($mysqli->error . "[$queryDelWP]");
        $queryDelItem = "DELETE FROM products WHERE type = '" . $blog['1'] . "' AND blogId = '" . $blog['0'] . "'";
        $resultDelItem = $mysqli->query($queryDelItem) OR trigger_error($mysqli->error . "[$queryDelItem]");
        $queryDelKeyword = "DELETE FROM keywords WHERE type = '" . $blog['1'] . "' AND blogId = '" . $blog['0'] . "'";
        $resultDelKeyword = $mysqli->query($queryDelKeyword) OR trigger_error($mysqli->error . "[$queryDelKeyword]");
        $queryDelTask = "DELETE FROM scheduler WHERE scriptpath LIKE'%wpID=" . $blog['0'] . "'";
        $resultDelTask = $mysqli->query($queryDelTask) OR trigger_error($mysqli->error . "[$queryDelTask]");
        $queryDelLog = "DELETE FROM error_log WHERE type = '" . $blog['1'] . "' AND blogId = '" . $blog['0'] . "'";
        $resultDelLog = $mysqli->query($queryDelLog) OR trigger_error($mysqli->error . "[$queryDelTask]");     
        $alertMsg = 'alert-success';
        $logMsg = '<p><span class="label label-success">Success</span> Delete blog ID: <strong>' . $blog['0'] . '</strong></p>';
    }

    if ($_POST['action'] == 'delete-log') {
        $alertMsg = 'alert-success';
        if (isset($_POST['delete-item'])) {
            $deleteItem = slashes($_POST['delete-item']);
        }

        if (!empty($_POST['logID'])) {
            if (!is_array($_POST['logID'])) {
                $deleteItem[] = slashes($_POST['logID']);
            }
        }

        foreach ($deleteItem as $deleteID) {
            $queryDelLog = "DELETE FROM error_log WHERE ID = '" . $deleteID . "'";
            $resultDelLog = $mysqli->query($queryDelLog) OR trigger_error($mysqli->error . "[$queryDelTask]");
        }
    }
}

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
$queryError = "SELECT * FROM error_log";
if (isset($_GET['search'])) {
    $search = slashes($_GET['search']);
    $queryError .= " WHERE blog_url LIKE '%" . $search . "%'";
} else {
    $search = NULL;
}

$resultTotal = $mysqli->query($queryError)->num_rows OR trigger_error($mysqli->error . "[$queryError]");
$queryData = $queryError . " LIMIT $eu, $limit";
$resultError = $mysqli->query($queryData) OR trigger_error($mysqli->error . "[$queryData]");

?>

                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-bug color"></i> <?php echo $module['title']; ?></h2>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <div class="content-body">
                                    <?php if (isset($_POST['action'])) { ?>
                                    <div class="alert <?php echo $alertMsg; ?>">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <?php if ($_POST['action'] == 'delete-blog') {
                                            echo $logMsg;
                                        }
                                        if ($_POST['action'] == 'delete-log') {
                                            foreach ($deleteItem as $deletedLog) { ?>
                                                <p><span class="label label-success">Success</span> Delete log ID: <strong><?php echo $deletedLog ?></strong></p>
                                            <?php }
                                        } ?>
                                    </div>
                                    <?php } ?>
                                    <form name ="tool-form" action="" method="GET">
                                        <input type="hidden" name="module" value="error-log" />
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
                                                    <th class="col-md-3">Blog URL</th>
                                                    <th class="col-md-4">Reason</th>
                                                    <th class="col-md-3">Datetime</th>
                                                    <th class="col-md-2">Action</th>
                                                    <th class="col-md-1 text-center"><input type="checkbox" name="select-all" class="select-all" data-checkbox-name="delete-item[]" /></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($errorRow = $resultError->fetch_array(MYSQLI_ASSOC)) { ?>
                                                <tr>
                                                    <td><?php echo shortString($errorRow['blog_url'],'25'); ?></td>
                                                    <td><?php echo shortString($errorRow['reason'], '36'); ?></td>
                                                    <td><?php echo strftime("%H:%M:%S on %b %d, %Y", $errorRow['datetime']); ?></td>
                                                    <td>
                                                        <a href="?module=wordpress&m-option=edit-wordpress&id=<?php echo $errorRow['blogId']; ?>" class="btn btn-xs yellow"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></a>
                                                        <a href="#delete-blog" class="btn btn-xs blue deleteBlog" data-toggle="modal" data-id="<?php echo $errorRow['blogId'] . ', ' . $errorRow['type']; ?>"><i class="fa fa-times" data-toggle="tooltip" title="Dalete Blog"></i></a>
                                                        <a href="#delete-log" class="btn btn-xs red deleteLog" data-toggle="modal" data-id="<?php echo $errorRow['ID']; ?>"><i class="fa fa-times" data-toggle="tooltip" title="Delete Log"></i></a>
                                                    </td>
                                                    <td class="text-center"><input type="checkbox" name="delete-item[]" value="<?php echo $errorRow['ID']; ?>" /></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <!-- BEGIN #delete-log MODAL -->
                                        <div id="delete-log" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h3 id="modal-title">Delete</h3>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure that you want to permanently delete the error logs?</p>
                                                        <input type="hidden" name="logID" id="logID" value=""/>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn white" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                                        <button type="submit" class="btn red" name="action" value="delete-log">Yes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END #delete-blog MODAL -->
                                        <!-- BEGIN #delete-blog MODAL -->
                                        <div id="delete-blog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h3 id="modal-title">Delete</h3>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure that you want to permanently delete the error blogs?</p>
                                                        <input type="hidden" name="blogID" id="blogID" value=""/>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn white" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                                        <button type="submit" class="btn red" name="action" value="delete-blog">Yes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END #delete MODAL -->
                                    </form>
                                    <div class="row">
                                        <div class="col-md-3 text-left">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <ul class="pagination">
                                                    <?php echo pagination($resultTotal, $page, $limit, 'page'); ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <a href="#delete-log" class="btn red" data-toggle="modal"><i class="fa fa-times"></i> Delete Log</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- end: CONTENT BODY -->
                            </div>

