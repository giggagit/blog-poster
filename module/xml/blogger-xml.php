<?php

require_once 'lib/lib.xml-rpc.php';
require_once 'lib/lib.csv.php';

if (isset($_POST['action'])) {
    set_include_path(realpath(__DIR__) . '/../../lib');
    $getBG = slashes($_POST);
    $excludeMerchant = str_replace(', ', ', !', substr_replace($options['exclude_merchant'], '!', '0', '0'));
    $excludeMerchant = explode(', ', $excludeMerchant);
    $getBG['keywords'] = array_filter(explode("\n", str_replace("\r", "", $getBG['keywords'])));
    $created = gmdate('Y-m-d\TH:i:s\Z', time());
    $pubDate = rssDate();
    $dataItem[] = '';
    $i = '1';
    $countPost = '1';
    $bgXML = '<?xml version="1.0" encoding="UTF-8"?>
<ns0:feed xmlns:ns0="http://www.w3.org/2005/Atom">
<ns0:title type="html">Prosperent Hunter</ns0:title>
<ns0:generator>Prosperent Hunter/' . $version . '</ns0:generator>
<ns0:link href="http://wordpress.org" rel="self" type="application/atom+xml"/>
<ns0:link href="http://wordpress.org" rel="alternate" type="text/html"/>
<ns0:updated>' . $created . '</ns0:updated>
';

    foreach ($getBG['keywords'] as $keyGroups) {
        $keyGroups = explode("|", $keyGroups);
        $keywords = $keyGroups['0'];
        $connectProsperent = connectProsperent($options['primary_api'], $keywords, $getBG['serpLimit'], '', $excludeMerchant, '', '', '', $getBG['tracking'], $options['cache_dir']);
        foreach($connectProsperent['data'] as $item) {
            array_push($dataItem, $item);
            if (empty($keyGroups['1'])) {
                 $dataItem[$i]['wp_category'] = 'Uncategorized';
            } else {
                $dataItem[$i]['wp_category'] = $keyGroups['1'];
            }

            $i++;
        }

        if (isset($connectProsperent['error'])) {
            $_POST['action'] = 'error';
            $alertMsg = 'alert-danger';
            $wpMsg = '<p><span class="label label-danger">Error</span> There is something wrong</p>';
        } else {
            $dataProsperent = array_filter($dataItem);
        }
    }

    foreach($dataProsperent as $getData) {
        $getItem = slashes($getData);
        shuffle($getBG['template']);
        $postDate = randDate(date('Ymd', strtotime('-' . $options['random_date'] . ' day', time())), date('Ymd', time()), 'bgxml');
        $item_catalogId = $getItem['catalogId'];
        $item_productId = $getItem['productId'];
        $item_affiliate_url = $getItem['affiliate_url'];
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
        $getProducts[] = '<p><span class="label label-success">Success</span> Product ID: ' . $getItem['productId'] . '</p>';
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
            $contents = str_replace("\n", '', $contents);
            $contents = str_replace("\r", '', $contents);
            $contents = str_replace("\r\n", '', $contents);
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
            $contents = str_replace("##BRANDLOGO##', $brandLogo, $contents);
            $contents = str_replace("##SUBFIX##', $subFix, $contents);
            $contents = str_replace("##PREFIX##', $preFix, $contents);*/
        }

        $bgXML .= '<ns0:entry>';
        $title = htmlentities($title, ENT_NOQUOTES, 'UTF-8');
        $tag = tag($item_keyword);
        $cate = explode(',', $tag);
        $catarr = '';
        foreach ($cate as $wpTag) {
            $bgtag = preg_replace('/[^A-Za-z0-9-]/', ' ', $wpTag);
                if ($wpTag != "") {
                    $bgXML .= '
    <ns0:category scheme="http://www.blogger.com/atom/ns#" term="' . $wpTag . '" />';
                }
        }

        $titleSlug = slug($item_keyword);
        $bgXML .= '
    <ns0:category scheme="http://schemas.google.com/g/2005#kind" term="http://schemas.google.com/blogger/2008/kind#post" />
    <ns0:id>post-' . $countPost . '</ns0:id>
    <ns0:author>
        <ns0:name>admin</ns0:name>
    </ns0:author>
    <ns0:content type="html">' . htmlContent($contents) . '"</ns0:content>
    <ns0:published>' . $postDate . '</ns0:published>
    <ns0:title type="html">' . htmlContent($title) . '</ns0:title>
    <ns0:link href="http://wordpress.org/' . $titleSlug . '/" rel="self" type="application/atom+xml" />
    <ns0:link href="http://wordpress.org/' . $titleSlug . '/" rel="alternate" type="text/html" />
</ns0:entry>
';

        $countPost++;
    }

    $bgXML .="</ns0:feed>";
    $flieXML = 'module/xml/download/blogger/' . date("Y-m-d_H-i-s") . '.xml';
    $bgXML = get_magic_quotes_gpc() ? stripslashes($bgXML) : $bgXML;
    $fp = fopen($flieXML, 'w');
    fwrite($fp, $bgXML);
    fclose($fp);
    $alertMsg = 'alert-success';
    $bgMsg = '<p>Create blogger XML file: <strong><a href="' . $flieXML . '">Download (Right click and save link as)</a></strong></p>';
    echo '<pre>' . htmlentities($bgXML) . '</pre>';
}

$queryTemplate = "SELECT * FROM template";
$resultTemplate = $mysqli->query($queryTemplate) OR trigger_error($mysqli->error . "[$queryTemplate]");

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
                                        <?php if ($_POST['action'] == 'generator') {
                                            echo $wpMsg;
                                            foreach ($getProducts as $products) { ?>
                                                <?php echo $products ?>
                                            <?php }
                                        } ?>
                                    </div>
                                    <?php } ?>
                                    <form name="wordpress-xml" class="form-horizontal" data-toggle="validator" action="" method="POST">
                                        <input type="hidden" name="action" value="generator" />
                                        <h3>Blog Info</h3>
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
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Options</label>
                                                    <div class="col-md-10">
                                                        <p><input type="checkbox" name="option_aio" value="yes" /> <label>All in One SEO Pack</label></p>
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

