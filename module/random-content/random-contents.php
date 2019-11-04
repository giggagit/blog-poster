<?php

if ($module['module'] == 'header-contents') {
    $contentModule = 'header';
}

if ($module['module'] == 'footer-contents') {
    $contentModule = 'footer';
}

if (isset($_POST['action'])) {
    $getContent = slashes($_POST);
    if ($_POST['action'] == 'add') {
        $queryAdd = "INSERT INTO random_contents (ID, contents, type) VALUES (NULL, '" . $getContent['content'] . "', '" . $contentModule . "')";
        $resultAdd = $mysqli->query($queryAdd) or trigger_error($mysqli->error . "[$queryAdd]");
        $alertMsg = 'alert-success';
        $tempMsg = '<p><span class="label label-success">Success</span> Add random ' . $contentModule . ' content</p>';
    }

    if ($_POST['action'] == 'edit') {
        $queryEdit = "UPDATE random_contents SET contents = '" . $getContent['content'] . "' WHERE ID = '" . $getContent['contentID'] . "'";
        $resultEdit = $mysqli->query($queryEdit) or trigger_error($mysqli->error . "[$queryEdit]");
        $alertMsg = 'alert-success';
        $tempMsg = '<p><span class="label label-success">Success</span> Edit random ' . $contentModule . ' content ID: ' . $getContent['contentID'] . '</p>';
    }

    if ($_POST['action'] == 'delete') {
        $alertMsg = 'alert-success';
        if (isset($_POST['delete-item'])) {
            $deleteItem = slashes($_POST['delete-item']);
        }

        if (!empty($_POST['itemID'])) {
            if (!is_array($_POST['itemID'])) {
                $deleteItem[] = slashes($_POST['itemID']);
            }
        }

        foreach ($deleteItem as $deleteID) {
            $querydelTemplate = "DELETE FROM random_contents WHERE id = '" . $deleteID . "'";
            $resultdelTemplate = $mysqli->query($querydelTemplate) OR trigger_error($mysqli->error . "[$querydelTemplate]");
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
$queryContent = "SELECT * FROM random_contents WHERE type = '" . $contentModule . "'";
if (isset($_GET['search'])) {
    $search = slashes($_GET['search']);
    $queryContent .= " AND contents LIKE '%" . $search . "%'";
} else {
    $search = NULL;
}

$resultTotal = $mysqli->query($queryContent)->num_rows OR trigger_error($mysqli->error . "[$queryContent]");
$queryData = $queryContent . " LIMIT $eu, $limit";
$resultContent = $mysqli->query($queryData) OR trigger_error($mysqli->error . "[$queryData]");

?>

                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-file-text-o color"></i> <?php echo $module['title']; ?></h2>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <div class="content-body">
                                    <?php if (isset($_POST['action'])) { ?>
                                    <div class="alert <?php echo $alertMsg; ?>">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <?php if ($_POST['action'] == 'add') {
                                            echo $tempMsg;
                                        }
                                        if ($_POST['action'] == 'edit') {
                                            echo $tempMsg;
                                        }
                                        if ($_POST['action'] == 'delete') {
                                            foreach ($deleteItem as $deletedTemp) { ?>
                                                <p><span class="label label-success">Success</span> Delete random <?php echo $contentModule; ?> content ID: <strong><?php echo $deletedTemp ?></strong></p>
                                            <?php }
                                        } ?>
                                    </div>
                                    <?php } ?>
                                    <form name ="tool-form" action="" method="GET">
                                        <input type="hidden" name="module" value="<?php echo $module['module']; ?>" />
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
                                    <form name="<?php echo $module['module']; ?>-form" action="" method="POST">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="col-md-11">Contents</th>
                                                    <th class="col-md-1">Action</th>
                                                    <th class="col-md-1 text-center"><input type="checkbox" name="select-all" class="select-all" data-checkbox-name="delete-item[]" /></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($contentRow = $resultContent->fetch_array(MYSQLI_ASSOC)) { ?>
                                                <tr>
                                                    <td><?php echo shortString(strip_tags($contentRow['contents']), '90'); ?></td>
                                                    <td>
                                                        <a href="?module=<?php echo $module['module']; ?>&m-option=edit&id=<?php echo $contentRow['ID']; ?>" class="btn btn-xs yellow"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></a>
                                                        <a href="#delete" class="btn btn-xs red deleteItem" data-toggle="modal" data-id="<?php echo $contentRow['ID']; ?>"><i class="fa fa-times" data-toggle="tooltip" title="Delete"></i></a>
                                                    </td>
                                                    <td class="text-center"><input type="checkbox" name="delete-item[]" value="<?php echo $contentRow['ID']; ?>" /></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <!-- BEGIN #delete MODAL -->
                                        <div id="delete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h3 id="modal-title">Delete</h3>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure that you want to permanently delete the selected contents?</p>
                                                        <input type="hidden" name="itemID" id="itemID" value=""/>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn white" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                                        <button type="submit" class="btn red" name="action" value="delete">Yes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END #delete MODAL -->
                                    </form>
                                    <div class="row">
                                        <div class="col-md-3 text-left">
                                            <a href="?module=<?php echo $module['module']; ?>&m-option=add" class="btn green"><i class="fa fa-plus"></i> Add Content</a>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-center">
                                                <ul class="pagination">
                                                    <?php echo pagination($resultTotal, $page, $limit, 'page'); ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <a href="#delete" class="btn red" data-toggle="modal"><i class="fa fa-times"></i> Delete</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- end: CONTENT BODY -->
                            </div>

