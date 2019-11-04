<?php

if (isset($_POST['action'])) {
    $getTemplate = slashes($_POST);
    if (!empty($getTemplate['options'])) {
        $getoption = $getTemplate['options'];
        $tempOptions = '';
        foreach ($getoption as $name => $value) {
            $tempOptions .= $name . '|' . $value . ', ';
        }

        $tempOptions = preg_replace('/(, )$/', '', $tempOptions);
    }

    if ($_POST['action'] == 'add') {
        $queryAdd = "INSERT INTO template (ID, name, title, prefix, suffix, compare_table, post_template, options) VALUES (NULL, '" . $getTemplate['template-name'] . "', '" . $getTemplate['post-title'] . "', '" . $getTemplate['prefix'] . "', '" . $getTemplate['suffix'] . "', '" . $getTemplate['compare-table'] . "', '" . $getTemplate['post-template'] . "', '" . $tempOptions . "')";
        $resultAdd = $mysqli->query($queryAdd) OR trigger_error($mysqli->error . "[$queryAdd]");
        $alertMsg = 'alert-success';
        $tempMsg = '<p><span class="label label-success">Success</span> Add template name: <strong>' . $getTemplate['template-name'] . '</strong></p>';
    }

    if ($_POST['action'] == 'edit') {
        $queryEdit = "UPDATE template SET name = '" . $getTemplate['template-name'] . "', title = '" . $getTemplate['post-title'] . "', prefix = '" . $getTemplate['prefix'] . "', suffix = '" . $getTemplate['suffix'] . "', compare_table = '" . $getTemplate['compare-table'] . "', post_template = '" . $getTemplate['post-template'] . "', options ='" . $tempOptions . "' WHERE ID = '" . $getTemplate['tempID'] . "'";
        $resultEdit = $mysqli->query($queryEdit) OR trigger_error($mysqli->error . "[$queryEdit]");
        $alertMsg = 'alert-success';
        $tempMsg = '<p><span class="label label-success">Success</span> Edit template name: <strong>' . $getTemplate['template-name'] . '</strong></p>';
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
            $querydelTemplate = "DELETE FROM template WHERE id = '" . $deleteID . "'";
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
$queryTemplate = "SELECT * FROM template";
if (isset($_GET['search'])) {
    $search = slashes($_GET['search']);
    $queryTemplate .= " WHERE name LIKE '%" . $search . "%'";
} else {
    $search = NULL;
}

$resultTotal = $mysqli->query($queryTemplate)->num_rows OR trigger_error($mysqli->error . "[$queryTemplate]");
$queryData = $queryTemplate . " LIMIT $eu, $limit";
$resultTemplate = $mysqli->query($queryData) OR trigger_error($mysqli->error . "[$queryData]");

?>

                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-file color"></i> <?php echo $module['title']; ?></h2>
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
                                                <p><span class="label label-success">Success</span> Delete log ID: <strong><?php echo $deletedTemp ?></strong></p>
                                            <?php }
                                        } ?>
                                    </div>
                                    <?php } ?>
                                    <form name ="tool-form" action="" method="GET">
                                        <input type="hidden" name="module" value="template" />
                                        <div class="row">
                                            <div class="form-group col-md-6">
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
                                            <div class="input-group col-md-6">
                                                <div class="input-group">
                                                    <input type="text" name="search" class="form-control" placeholder="Search..." value="<?php echo $search; ?>">
                                                    <span class="input-group-btn"><button type="submit" class="btn default">Go!</button></span>
                                                </div>
                                            </div>
                                        </div>
                                        <noscript><input type="submit" value="Submit"></noscript>
                                    </form>
                                    <form name="template-form" action="" method="POST">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th class="col-md-11">Template Name</th>
                                                    <th class="col-md-1">Action</th>
                                                    <th class="col-md-1 text-center"><input type="checkbox" name="select-all" class="select-all" data-checkbox-name="delete-item[]" /></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($templateRow = $resultTemplate->fetch_array(MYSQLI_ASSOC)) { ?>
                                                <tr>
                                                    <td><?php echo $templateRow['name']; ?></td>
                                                    <td>
                                                        <a href="?module=template&m-option=edit-template&id=<?php echo $templateRow['ID']; ?>" class="btn btn-xs yellow"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></a>
                                                        <a href="#delete" class="btn btn-xs red deleteItem" data-toggle="modal" data-id="<?php echo $templateRow['ID']; ?>"><i class="fa fa-times" data-toggle="tooltip" title="Delete"></i></a>
                                                    </td>
                                                    <td class="text-center"><input type="checkbox" name="delete-item[]" value="<?php echo $templateRow['ID']; ?>" /></td>
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
                                                        <p>Are you sure that you want to permanently delete the selected templates?</p>
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
                                            <a href="?module=template&m-option=add-template" class="btn green"><i class="fa fa-plus"></i> Add Template</a>
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

