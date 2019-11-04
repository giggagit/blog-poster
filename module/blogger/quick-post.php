<?php

require_once 'lib/lib.xml-rpc.php';
require_once 'lib/lib.csv.php';

if (!empty($_POST)) {
    set_include_path(realpath(__DIR__) . '/../../lib');
    $getBG = slashes($_POST);
    $bg_blogurl = $getBG['blogurl'];
    $bg_blogID = $getBG['blogid'];
    $bg_email = $getBG['email'];
    $bg_password = $getBG['password'];
    $excludeMerchant = str_replace(', ', ', !', substr_replace($options['exclude_merchant'], '!', '0', '0'));
    $excludeMerchant = explode(', ', $excludeMerchant);
    $getBG['keywords'] = array_filter(explode("\n", str_replace("\r", "", $getBG['keywords'])));
    $dataItem[] = '';
    foreach ($getBG['keywords'] as $keyGroups) {
        $keyGroups = explode("|", $keyGroups);
        $keywords = $keyGroups['0'];
        $connectProsperent = connectProsperent($options['primary_api'], $keywords, $getBG['serpLimit'], '', $excludeMerchant, '', '', '', $getBG['tracking'], $options['cache_dir']);
        foreach($connectProsperent['data'] as $item) {
            array_push($dataItem, $item);
        }

        if (isset($connectProsperent['error'])) {
            $_POST['action'] = 'error';
            $alertMsg = 'alert-danger';
            $bgMsg = '<p><span class="label label-danger">Error</span> There is something wrong</p>';
        } else {
            $dataProsperent = array_filter($dataItem);
            $alertMsg = 'alert-success';
            $bgMsg = '<p>Post to blogger: <strong>' . $getBG['blogurl'] . '</strong></p>';
        }
    }

    foreach($dataProsperent as $getData) {
        $getItem = slashes($getData);
        shuffle($getBG['template']);
        $affiliateUrl = preg_replace('/referrer=?([a-zA-Z0-9_%\-&]*)+location=?([a-zA-Z0-9_%\-]*)/', 'referrer=' . urlencode($getBG['blogurl']) . '&location=' . urlencode($getBG['blogurl']) , $getItem['affiliate_url']);
        $item_catalogId = $getItem['catalogId'];
        $item_productId = $getItem['productId'];
        $item_affiliate_url = $affiliateUrl;
        $item_image_url = $getItem['image_url'];
        $item_keyword = $getItem['keyword'];
        $item_keywords = $getItem['keywords'];
        $item_celebrity = $getItem['celebrity'];
        $item_description = $getItem['description'];
        $item_category = $getItem['category'];
        $item_price = $getItem['price'];
        $item_price_sale = $getItem['price_sale'];
        $item_currency = $getItem['currency'];
        $item_merchant = $getItem['merchant'];
        $item_brand = $getItem['brand'];
        $item_upc = $getItem['upc'];
        $item_isbn = $getItem['isbn'];
        $item_sales = $getItem['sales'];
        $queryTemplate = "SELECT * FROM template WHERE ID = '" . $getBG['template']['0'] . "'";
        $resultTemplate = $mysqli->query($queryTemplate) OR trigger_error($mysqli->error . "[$queryTemplate]");
        if ($resultTemplate->num_rows <= '0') {
            exit('No Template');
        }

        while ($templateRow = $resultTemplate->fetch_array(MYSQLI_ASSOC)) {
            $template_title = $templateRow['title'];
            $template_description = $templateRow['post_template'];
            $template_compareTable = $templateRow['compare_table'];

            // Spin title
            $title = spin($template_title);
            $title = str_replace("##PRODUCTID##", $item_productId, $title);
            $title = str_replace("##URL##", $item_affiliate_url, $title);
            $title = str_replace("##IMAGE##", $item_image_url, $title);
            $title = str_replace("##TITLE##", $item_keyword, $title);
            $title = str_replace("##DESCRIPTION##", $item_description, $title);
            $title = str_replace("##CATEGORY##", $item_category, $title);
            $title = str_replace("##PRICE##", $item_price, $title);
            $title = str_replace("##PRICESALE##", $item_price_sale, $title);
            $title = str_replace("##CURRENCY##", $item_currency, $title);
            $title = str_replace("##MERCHANT##", $item_merchant, $title);
            $title = str_replace("##BRAND##", $item_brand, $title);
            $title = str_replace("##UPC##", $item_upc, $title);
            //$title = str_replace("##SUBFIX##', $subFix, $title);
            //$title = str_replace("##PREFIX##', $preFix, $title);

            // Spin content
            $contents = spin($template_description);
            $contents = str_replace("##PRODUCTID##", $item_productId, $contents);
            $contents = str_replace("##URL##", $item_affiliate_url, $contents);
            $contents = str_replace("##IMAGE##", $item_image_url, $contents);
            $contents = str_replace("##TITLE##", $item_keyword, $contents);
            $contents = str_replace("##DESCRIPTION##", $item_description, $contents);
            $contents = str_replace("##CATEGORY##", $item_category, $contents);
            $contents = str_replace("##PRICE##", $item_price, $contents);
            $contents = str_replace("##PRICESALE##", $item_price_sale, $contents);
            $contents = str_replace("##CURRENCY##", $item_currency, $contents);
            $contents = str_replace("##MERCHANT##", $item_merchant, $contents);
            $contents = str_replace("##BRAND##", $item_brand, $contents);
            $contents = str_replace("##UPC##", $item_upc, $contents);
            /*$contents = str_replace("##COMPARE##', $compareTable, $contents);
            $contents = str_replace("##MERCHANTLOGO##', $merchantLogo, $contents);
            $contents = str_replace("##BRANDLOGO##', $brandLogo, $contents);*/

            /*$contents = str_replace("##SUBFIX##', $subFix, $contents);
            $contents = str_replace("##PREFIX##', $preFix, $contents);*/
        }

        $bgPoster = bgPostXMLRPC($bg_email, $bg_password, $bg_blogID, $title, $contents, tag($item_keyword));
        if (is_numeric($bgPoster['0'])) {
            if (empty($options['random_time'])) {
                $options['random_time'] = '3600';
            }
            
            $getProducts[] = '<p><span class="label label-success">Posted</span> ' . $item_keyword . ' <a href="' . $bgPoster['1'] . '" target="_blank">Views</a><p>';
        } else {
            $getProducts[] = '<p><span class="label label-danger">Error</span> Product ID: ' . $item_productId . '</p>';
        }
    }
}

$queryTemplate = "SELECT * FROM template";
$resultTemplate = $mysqli->query($queryTemplate) OR trigger_error($mysqli->error . "[$queryTemplate]");

?>

                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-th color"></i> <?php echo $module['title']; ?></h2>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <div class="content-body">
                                    <?php if (isset($_POST['action'])) { ?>
                                    <div class="alert <?php echo $alertMsg; ?>">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <?php if ($_POST['action'] == 'quick-post') {
                                            echo $bgMsg;
                                            foreach ($getProducts as $products) { ?>
                                                <?php echo $products ?>
                                            <?php }
                                        } ?>
                                    </div>
                                    <?php } ?>
                                    <form name="quick-post" class="form-horizontal" data-toggle="validator" action="" method="POST">
                                        <input type="hidden" name="action" value="quick-post" />
                                        <h3>Blog Info</h3>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Blog URL:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="blogurl" class="form-control" placeholder="http://www.example.com" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Blog ID:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="blogid" class="form-control" placeholder="Blogger ID" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Email:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="email" class="form-control" placeholder="Username" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Password:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="password" class="form-control" placeholder="Password" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Post Start Date:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="start-post" class="form-control" placeholder="Post start date" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Post End Date:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="end-post" class="form-control" placeholder="Post end date" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h3>Preferences</h3>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">API:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="api" class="form-control" placeholder="API (Optional)" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Keywords:</label>
                                                    <div class="col-md-10">
                                                        <textarea name="keywords" class="form-control" rows="2" placeholder="Keywords|Category (One per line)" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Tracking:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="tracking" class="form-control" placeholder="Track a site's purchase through (Optional)" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Product Result:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="serpLimit" class="form-control" placeholder="Total post" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Brand:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="brand" class="form-control" placeholder="Filter by brand (Optional)" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Merchant:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="merchant" class="form-control" placeholder="Filter by merchant (Optional)" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Category:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="category" class="form-control" placeholder="Filter by categories (Optional)" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Max Price:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="maxPrice" class="form-control" placeholder="Filter by max price (Optional)" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Min Price:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="minPrice" class="form-control" placeholder="Filter by min price (Optional)" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Template:</label>
                                                    <div class="col-md-10">
                                                        <select name="template[]" class="form-control" multiple="multiple" required>
                                                            <?php while ($templateRow = $resultTemplate->fetch_array(MYSQLI_ASSOC)) { ?>
                                                            <option value="<?php echo $templateRow['ID']; ?>"><?php echo $templateRow['name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <input type="button"t name="select-all" class="form-control btn black" value="Select All" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                <div class="form-group">
                                                    <button type="submit" class="btn blue" disabled>Submit</button>
                                                    <button type="reset" class="btn red">Reset</button>
                                                    <button type="button" class="btn white" onClick="history.go(-1);return true;">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

