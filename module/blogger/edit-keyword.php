<?php

if (isset($_POST['confirm-delete'])) {
    if (isset($_POST['select-delete'])) {
        foreach ($_POST['select-delete'] as $deleteID) {
            $queryBdelItem = "DELETE FROM products WHERE ID = '" . $deleteID . "'";
            $resultBdelItem = $mysqli->query($queryBdelItem) OR trigger_error($mysqli->error . "[$queryBdelItem]");
        }
    }
}

if ($module['module'] == 'blogger') {
    $blogType = 'blogger';
}

if ($module['module'] == 'blogger') {
    $blogType = 'blogger';
}

if (isset($_GET['length'])) {
    $limit = slashes($_GET['length']);
} else {
    $limit = '10';
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
$queryBlog = "SELECT * FROM blogger";
if (isset($_GET['search'])) {
    $search = slashes($_GET['search']);
    $queryBlog .= " WHERE blog_url LIKE '%" . $search . "%'";
} else {
    $search = NULL;
}

$getKeyword = slashes($_GET);
$kID = $getKeyword['id'];
$queryKeywords = "SELECT * FROM keywords WHERE ID = '" . $kID . "'";
$resultKeywords = $mysqli->query($queryKeywords)->fetch_array(MYSQLI_ASSOC) OR trigger_error($mysqli->error . "[$queryKeywords]");
$queryItem = "SELECT * FROM products WHERE keywordId = '" . $kID . "'";
if (isset($_GET['search'])) {
    $search = $getKeyword['search'];
    $queryItem .= " AND (keyword LIKE '%" . $search . "%' OR merchant LIKE '%" . $search . "%')";
}

$resultTotal = $mysqli->query($queryItem)->num_rows OR trigger_error($mysqli->error . "[$queryItem]");
$queryData = $queryItem . " LIMIT $eu, $limit";
$resultItem = $mysqli->query($queryData) OR trigger_error($mysqli->error . "[$queryData]");

?>

                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-desktop color"></i> <?php echo $module['title'] . ': ' . $resultKeywords['keywords']; ?></h2>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <?php if (isset($_POST['confirm-delete'])) { ?>
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <?php foreach ($_POST['select-delete'] as $deleteID) { ?>
                                        <p>Deleted product ID: <?php echo $deleteID ?></p>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <div class="content-body">
                                    <form name ="tool-form" action="" method="GET">
                                        <input type="hidden" name="module" value="blogger" />
                                        <input type="hidden" name="m-option" value="edit-keyword" />
                                        <input type="hidden" name="id" value="<?php echo $kID; ?>" />
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
                                    <form name="edit-products" action="" method="POST">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-md-5">Product Name</th>
                                                            <th class="col-md-5">Merchant</th>
                                                            <th class="col-md-1">Status</th>
                                                            <th class="col-md-1 text-center"><input type="checkbox" name="select-all" class="select-all" data-checkbox-name="select-delete[]" /></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        while ($itemRow = $resultItem->fetch_array(MYSQLI_ASSOC)) {
                                                            $itemStatus = (($itemRow['status'] == 'Posted') ? 'label label-success' : 'label label-default');
                                                        ?>
                                                        <tr>
                                                            <td><?php shortString($itemRow['keyword'], '38'); ?></td>
                                                            <td><?php shortString($itemRow['merchant'], '20'); ?></td>
                                                            <td><span class="<?php echo $itemStatus; ?>"><?php echo $itemRow['status']; ?></span></td>
                                                            <td class="text-center"><input type="checkbox" name="select-delete[]" value="<?php echo $itemRow['ID']; ?>" /></td>
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                                <!-- start: #delete MODAL -->
                                                <div id="delete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h3 id="modal-title">Delete</h3>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure that you want to permanently delete the selected products?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn white" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                                                <button type="submit" class="btn red" name="confirm-delete" value="yes">Yes</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end: #delete MODAL -->
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-md-3 text-left"></div>
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
                            </div>

