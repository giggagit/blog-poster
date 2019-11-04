<?php

if (isset($_POST['action'])) {
    $getWP = slashes($_POST);
    if ($_POST['action'] == 'add') {
        set_include_path(realpath(__DIR__) . '/../../lib');
        $cronjob = $getWP['duration'] * $getWP['time-format'];
        $excludeMerchant =  str_replace(', ', ', !', substr_replace($options['exclude_merchant'], '!', '0', '0'));
        $excludeMerchant = explode(', ', $excludeMerchant);
        $getWP['keywords'] = array_filter(explode("\n", str_replace("\r", "", $getWP['keywords'])));
        $dataItem[] = '';
        foreach ($getWP['keywords'] as $keyGroups) {
            $keyGroups = explode("|", $keyGroups);
            $keywords = $keyGroups['0'];
            if (empty($keyGroups['1'])) {
                $category = 'Uncategorized';
            } else {
                $category = $keyGroups['1'];
            }

            $connectProsperent = connectProsperent($options['primary_api'], $keywords, $options['serp_limit'], '', $excludeMerchant, '', '', '', $getWP['tracking'], $options['cache_dir']);
            foreach($connectProsperent['data'] as $item) {
                array_push($dataItem, $item);
            }
        }

        if (isset($connectProsperent['error'])) {
            $_POST['action'] = 'error';
            $alertMsg = 'alert-danger';
            $wpMsg = '<p><span class="label label-danger">Error</span> There is something wrong</p>';
        } else {
            $dataProsperent = array_filter($dataItem);
            $queryWP = "INSERT INTO wordpress (ID, blog_url, username, password, api, template, cronjob, duration, timeformat) VALUES (NULL, '" . $getWP['blogurl'] . "', '" . $getWP['username'] . "', '" . $getWP['password'] . "', '" . $getWP['api'] . "', '" . implode(", ", $getWP['template']) . "', '" . $cronjob . "', '" . $getWP['duration'] . "', '" . $getWP['time-format'] . "')";  
            $resultWP = $mysqli->query($queryWP) OR trigger_error($mysqli->error . "[$queryWP]");
            $wpID = $mysqli->insert_id;
            $queryTask = "INSERT INTO scheduler (ID, scriptpath, type, blogId, blog_url, time_interval, fire_time) VALUES (NULL, '" . $options['site_url'] . "/module/poster/autoposter.php?wp=" . $wpID . "', 'wordpress', '" . $wpID . "', '" . $getWP['blogurl'] . "', '" . $cronjob . "', '" . (time() + $options['random_time']) . "')";
            $resultTask = $mysqli->query($queryTask) OR trigger_error($mysqli->error . "[$queryTask]");
            $queryKeywords = "INSERT INTO keywords (ID, keywords, category, type, blogId) VALUES (NULL, '" . $keywords . "', '" . $category . "', 'wordpress', '" . $wpID . "')";
            $resultKeywords = $mysqli->query($queryKeywords) OR trigger_error($mysqli->error . "[$queryKeywords]");
            $kID = $mysqli->insert_id;
            $alertMsg = 'alert-success';
            $wpMsg = '<p>Add wordpress: <strong>' . $getWP['blogurl'] . '</strong></p>';
            foreach($dataProsperent as $getData) {
                $getItem = slashes($getData);
                $affiliateUrl = preg_replace('/referrer=?([a-zA-Z0-9_%\-&]*)+location=?([a-zA-Z0-9_%\-]*)/', 'referrer=' . urlencode($getWP['blogurl']) . '&location=' . urlencode($getWP['blogurl']) , $getItem['affiliate_url']);
                $queryDup = "SELECT ID FROM products WHERE productId = '" . $getItem['productId'] . "'";
                $resultDup = $mysqli->query($queryDup) OR trigger_error($mysqli->error . "[$queryDup]");
                if ($resultDup->num_rows > '0') {
                    $getProducts[] = '<p><span class="label label-default">Duplicate</span> Product ID: ' . $getItem['productId'] . '</p>';
                } else {
                    $queryAdd = "INSERT INTO products (ID, type, blogId, keywordId, catalogId, productId, affiliate_url, image_url, keyword, keywords, celebrity, description, category, price, price_sale, currency, merchant, brand, upc, isbn, sales, status) VALUES (NULL, 'wordpress', '" . $wpID . "', '" . $kID . "', '" . $getItem['catalogId'] . "', '" . $getItem['productId'] . "', '" . $affiliateUrl . "', '" . $getItem['image_url'] . "', '" . $getItem['keyword'] . "', '" . $getItem['keywords'] . "', '" . str_replace("", "BLANK", $getItem['celebrity']) . "', '" . $getItem['description'] . "', '" . $getItem['category'] . "', '" . $getItem['price'] . "', '" . $getItem['price_sale'] . "', '" . $getItem['currency'] . "', '" . $getItem['merchant'] . "', '" . $getItem['brand'] . "', '" . $getItem['upc'] . "', '" . $getItem['isbn'] . "', '" . $getItem['sales'] . "', 'Waiting')";
                    $resultAdd = $mysqli->query($queryAdd) OR trigger_error($mysqli->error . "[$queryAdd]");
                    $getProducts[] = '<p><span class="label label-success">Success</span> Product ID: ' . $getItem['productId'] . '</p>';
                }
            }
        }
    }

    if ($_POST['action'] == 'edit') {
        $cronjob = $getWP['duration'] * $getWP['time-format'];
        $time = $getWP['duration'] * $getWP['time-format'];
        $queryEdit = "UPDATE wordpress SET blog_url = '" . $getWP['blogurl'] . "', username = '" . $getWP['username'] . "', password = '" . $getWP['password'] . "', api = '" . $getWP['api'] . "', template = '" . implode(', ', $getWP['template']) . "', cronjob = '" . $time . "', duration = '" . $getWP['duration'] . "', timeformat = '" . $getWP['time-format'] . "' WHERE ID = '" . $getWP['wpID'] . "'";
        $resultEdit = $mysqli->query($queryEdit) OR trigger_error($mysqli->error . "[$queryEdit]");
        $queryTask = "UPDATE scheduler SET blog_url = '" . $getWP['blogurl'] . "', time_interval = '" . $cronjob . "', fire_time = '" . (time() + $options['random_time']) . "' WHERE type = 'wordpress' AND blogID = '" . $getWP['wpID'] . "'";
        $resultTask = $mysqli->query($queryTask) or trigger_error($mysqli->error . "[$queryTask]");
        $alertMsg = 'alert-success';
        $wpMsg = '<p><span class="label label-success">Success</span> Edit wordpress:<strong> ' . $getWP['blogurl'] . '</strong></p>';
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
            $querydelWP = "DELETE FROM wordpress WHERE ID = '" . $deleteID . "'";
            $resultdelWP = $mysqli->query($querydelWP) OR trigger_error($mysqli->error . "[$querydelWP]");
            $querydelItem = "DELETE FROM products WHERE type = 'wordpress' AND blogId = '" . $deleteID . "'";
            $resultdelItem = $mysqli->query($querydelItem) OR trigger_error($mysqli->error . "[$querydelItem]");
            $querydelKeyword = "DELETE FROM keywords WHERE type = 'wordpress' AND blogId = '" . $deleteID . "'";
            $resultdelKeyword = $mysqli->query($querydelKeyword) OR trigger_error($mysqli->error . "[$querydelKeyword]");
            $querydelTask = "DELETE FROM scheduler where scriptpath like'%wp=" . $deleteID . "'";
            $resultdelTask = $mysqli->query($querydelTask) OR trigger_error($mysqli->error . "[$querydelTask]");
        }
    }
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
$queryBlog = "SELECT * FROM wordpress";
if (isset($_GET['search'])) {
    $search = slashes($_GET['search']);
    $queryBlog .= " WHERE blog_url LIKE '%" . $search . "%'";
} else {
    $search = NULL;
}

$resultTotal = $mysqli->query($queryBlog)->num_rows OR trigger_error($mysqli->error . "[$queryBlog]");
$queryData = $queryBlog . " LIMIT $eu, $limit";
$resultBlog = $mysqli->query($queryData) OR trigger_error($mysqli->error . "[$queryData]");

?>

                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-sitemap color"></i> <?php echo $module['title']; ?></h2>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <div class="content-body">
                                    <?php if (isset($_POST['action'])) { ?>
                                    <div class="alert <?php echo $alertMsg; ?>">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <?php if ($_POST['action'] == 'add') {
                                            echo $wpMsg;
                                            foreach ($getProducts as $products) { ?>
                                                <?php echo $products ?>
                                            <?php }
                                        }
                                        if ($_POST['action'] == 'edit') {
                                            echo $wpMsg;
                                        }
                                        if ($_POST['action'] == 'delete') {
                                            foreach ($deleteItem as $deletedID) { ?>
                                                <p><span class="label label-success">Success</span> Delete wordpress ID: <strong><?php echo $deletedID ?></strong></p>
                                            <?php }
                                        }
                                        if ($_POST['action'] == 'error') {
                                            echo $wpMsg;
                                        } ?>
                                    </div>
                                    <?php } ?>
                                    <form name ="tool-form" action="" method="GET">
                                        <input type="hidden" name="module" value="wordpress" />
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
                                                    <th class="col-md-5">Blog URL</th>
                                                    <th class="col-md-1">Posted</th>
                                                    <th class="col-md-1">Total</th>
                                                    <th class="col-md-3">Last Post</th>
                                                    <th class="col-md-2">Action</th>
                                                    <th class="col-md-1 text-center"><input type="checkbox" name="select-all" class="select-all" data-checkbox-name="delete-item[]" /></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($wpRow = $resultBlog->fetch_array(MYSQLI_ASSOC)) { ?>
                                                <tr>
                                                    <td><?php echo shortString($wpRow['blog_url'], '30'); ?></td>
                                                    <td>
                                                        <?php
                                                        $queryItem = "SELECT * FROM products WHERE blogId = '" . $wpRow['ID'] . "' AND type = 'wordpress' AND status = 'Posted'";
                                                        $resultItem = $mysqli->query($queryItem)->num_rows OR trigger_error($mysqli->error . "[$queryItem]");
                                                        echo $resultItem;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $queryPosted = "SELECT * FROM products WHERE blogId = '" . $wpRow['ID'] . "' AND type = 'wordpress'";
                                                        $resultPosted = $mysqli->query($queryPosted)->num_rows OR trigger_error($mysqli->error . "[$queryPosted]");
                                                        echo $resultPosted;
                                                        ?>
                                                    </td>
                                                    <td><?php echo strftime("%H:%M:%S on %b %d, %Y", $wpRow['lastpost']); ?></td>
                                                    <td>
                                                        <a href="?module=poster&s-post&wp=<?php echo $wpRow['ID']; ?>" class="btn btn-xs green"><i class="fa fa-play" data-toggle="tooltip" title="Manual Post"></i></a>
                                                        <a href="?module=poster&b-post&wp=<?php echo $wpRow['ID']; ?>" class="btn btn-xs blue"><i class="fa fa-forward" data-toggle="tooltip" title="Bulk Post"></i></a>
                                                        <a href="?module=wordpress&m-option=edit-wordpress&id=<?php echo $wpRow['ID']; ?>" class="btn btn-xs yellow"><i class="fa fa-pencil" data-toggle="tooltip" title="Edit"></i></a>
                                                        <a href="#delete" class="btn btn-xs red deleteItem" data-toggle="modal" data-id="<?php echo $wpRow['ID']; ?>"><i class="fa fa-times" data-toggle="tooltip" title="Delete"></i></a>
                                                    </td>
                                                    <td class="text-center"><input type="checkbox" name="delete-item[]" value="<?php echo $wpRow['ID']; ?>" /></td>
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
                                                        <p>Are you sure that you want to permanently delete the selected blogs?</p>
                                                        <input type="hidden" name="itemID" id="itemID" value=""/>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn white" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                                        <button type="submit" class="btn red" name="action" value="delete">Yes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end: #delete MODAL -->
                                    </form>
                                    <div class="row">
                                        <div class="col-md-3 text-left">
                                            <a href="?module=wordpress&m-option=add-wordpress" class="btn green"><i class="fa fa-plus"></i> Add Blog</a>
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

