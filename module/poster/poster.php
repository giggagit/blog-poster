<?php

set_time_limit('0');
include 'lib/lib.xml-rpc.php';
include 'lib/lib.csv.php';

?>

                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-desktop color"></i> <?php echo $module['title']; ?></h2>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <div class="content-body">

<?php

if ((isset($_REQUEST['wp'])) OR (isset($_REQUEST['bg']))) {
    $now = time();
    if (isset($_REQUEST['wp'])) {
        $wpID = $_REQUEST['wp'];
        if (isset($_REQUEST['m-post'])) {
            $queryItem = "SELECT * FROM products WHERE ID = '" . trim($_REQUEST['m-post']) . "' limit 0,1";
        }

        if (isset($_REQUEST['s-post'])) {
            $queryItem = "SELECT * FROM products WHERE blogId = '" . $wpID . "' AND status = 'Waiting'  AND type = 'wordpress' ORDER BY RAND() limit 0,1";
        }

        if (isset($_REQUEST['b-post'])) {
            $queryItem = "SELECT * FROM products WHERE blogId = '" . $wpID . "' AND status = 'Waiting'  AND type = 'wordpress' ORDER BY RAND() LIMIT 0," . $options['bulk_post'];
        }

        $queryID = "SELECT * FROM wordpress WHERE ID ='" . $wpID . "'";
        $resultID = $mysqli->query($queryID)->fetch_array(MYSQLI_ASSOC) OR trigger_error($mysqli->error . "[$queryID]");
        $wp_blogurl = $resultID['blog_url'];
        $wp_username = $resultID['username'];
        $wp_password = $resultID['password'];
        $template = $resultID['template'];
        $cronjob = $resultID['cronjob'];
    }

    if (isset($_REQUEST['bg'])) {
        $bgID = $_REQUEST['bg'];
        if (isset($_REQUEST['m-post'])) {
            $queryItem = "SELECT * FROM products WHERE ID = '" . trim($_REQUEST['m-post']) . "' limit 0,1";
        }

        if (isset($_REQUEST['s-post'])) {
            $queryItem = "SELECT * FROM products WHERE blogId = '" . $bgID . "' AND status = 'Waiting'  AND type = 'blogger' ORDER BY RAND() limit 0,1";
        }

        if (isset($_REQUEST['b-post'])) {
            $queryItem = "SELECT * FROM products WHERE blogId = '" . $bgID . "' AND status = 'Waiting'  AND type = 'blogger' ORDER BY RAND() LIMIT 0," . $options['bulk_post'];
        }

        $queryID = "SELECT * FROM blogger WHERE ID ='" . $bgID . "'";
        $resultID = $mysqli->query($queryID)->fetch_array(MYSQLI_ASSOC) OR trigger_error($mysqli->error . "[$queryID]");
        $bg_blogurl = $resultID['blog_url'];
        $bg_blogID = $resultID['blogId'];
        $bg_email = $resultID['email'];
        $bg_password = $resultID['password'];
        $template = $resultID['template'];
        $cronjob = $resultID['cronjob'];
    }

    $resultItem = $mysqli->query($queryItem) OR trigger_error($mysqli->error . "[$queryItem]");
    if ($resultItem->num_rows > '0') {
        $temp = explode(',', $template);
        $template_options = [];
        shuffle($temp);
        foreach ($temp as $temps) {
            if ($temps != '') {
                $templateID = trim($temps);
            }
        }
        
        $queryTemplate = "SELECT * FROM template WHERE ID = '" . $templateID . "'";
        $resultTemplate = $mysqli->query($queryTemplate) OR trigger_error($mysqli->error . "[$queryTemplate]");
        if ($resultTemplate->num_rows <= '0') {
            if (isset($_REQUEST['wp'])) {
                $queryNotemp = "INSERT INTO error_log (ID, type, blogId, blog_url, reason, datetime) VALUES (NULL, 'wordpress', '" . $wpID . "', '" . $wp_blogurl . "', 'No Template', '" . $now . "')";
                $resultNoitem = $mysqli->query($queryNotemp) OR trigger_error($mysqli->error . "[$queryNotemp]");
            }

            if (isset($_REQUEST['bg'])) {
               $queryNotemp = "INSERT INTO error_log (ID, type, blogId, blog_url, reason, datetime) VALUES (NULL, 'blogger', '" . $bgID . "', '" . $bg_blogurl . "', 'No Template', '" . $now . "')";
                $resultNoitem = $mysqli->query($queryNotemp) OR trigger_error($mysqli->error . "[$queryNotemp]");
            }

            exit('No Template');
        }
        
        while ($templateRow = $resultTemplate->fetch_array(MYSQLI_ASSOC)) {
            $template_title = $templateRow['title'];
            $template_description = $templateRow['post_template'];
            $template_compareTable = $templateRow['compare_table'];
            $getOptions = array_filter(explode( ', ', $templateRow['options']));
            if (isset($getOptions)) {
                foreach ($getOptions as $tempOptions) {
                    $tempOptions = explode('|', $tempOptions);
                    array_push($template_options, array('key' => $tempOptions['0'], 'value' => $tempOptions['1']));
                }
            }
        }

        while ($itemRow = $resultItem->fetch_array(MYSQLI_ASSOC)) {
            $queryKID = "SELECT * FROM keywords WHERE ID ='" . $itemRow['keywordId'] . "'";
            $resultKID = $mysqli->query($queryKID)->fetch_array(MYSQLI_ASSOC) OR trigger_error($mysqli->error . "[$queryKID]");
            $category = $resultKID['category'];
            $itemID = $itemRow['ID'];
            $item_catalogId = $itemRow['catalogId'];
            $item_productId = $itemRow['productId'];
            $item_affiliate_url = $itemRow['affiliate_url'];
            $item_image_url = $itemRow['image_url'];
            $item_keyword = $itemRow['keyword'];
            $item_keywords = $itemRow['keywords'];
            $item_celebrity = $itemRow['celebrity'];
            $item_description = $itemRow['description'];
            $item_category = $itemRow['category'];
            $item_price = $itemRow['price'];
            $item_price_sale = $itemRow['price_sale'];
            $item_currency = $itemRow['currency'];
            $item_merchant = $itemRow['merchant'];
            $item_brand = $itemRow['brand'];
            $item_upc = $itemRow['upc'];
            $item_isbn = $itemRow['isbn'];
            $item_sales = $itemRow['sales'];

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

            if (isset($_REQUEST['wp'])) {
                if (isset($_REQUEST['b-post'])) {
                    $now = randDate(date('Ymd', strtotime('-' . $options['random_date'] . ' day', time())), date('Ymd', time()), 'wp');
                }

                $wpPoster = wpPostXMLRPC($title, $contents, $wp_blogurl, $wp_username, $wp_password, $category, $wpID, tag($item_keyword), $template_options, $now, 'nomal-post');
                if (is_numeric($wpPoster)) {
                    if (empty($options['random_time'])) {
                        $options['random_time'] = '3600';
                    }

                    $randCron = (rand('0', $options['random_time']));
                    $queryPosted = "UPDATE products SET status = 'Posted' WHERE ID = '" . $itemID . "'";
                    $resultPosted = $mysqli->query($queryPosted) OR trigger_error($mysqli->error . "[$queryPosted]");
                    $queryUpdateWP = "UPDATE wordpress SET lastpost = '" . $now . "' WHERE ID = '" . $wpID . "'";
                    $resultWP = $mysqli->query($queryUpdateWP) OR trigger_error($mysqli->error . "[$queryUpdateWP]");
                    $queryTask = "UPDATE scheduler SET last_fired = '" . $now . "', fire_time = '" . ($now + $cronjob + $randCron) . "' WHERE scriptpath like '%wp=" . $wpID . "'";
                    $resultTask = $mysqli->query($queryTask) OR trigger_error($mysqli->error . "[$queryTask]");
                    echo '<p><strong style="color:green">Posted:</strong> ' . $item_keyword . ' <a href="' . $wp_blogurl . '/?p=' . $wpPoster .'" target="_blank">Views</a></p>';
                } else {
                    echo '<p>' . $wpPoster . '</p>';
                }
            }

            if (isset($_REQUEST['bg'])) {
                set_include_path(realpath(__DIR__) . '/../../lib');
                $bgPoster = bgPostXMLRPC($bg_email, $bg_password, $bg_blogID, $title, $contents, tag($item_keyword));
                if (is_numeric($bgPoster['0'])) {
                    if (empty($options['random_time'])) {
                        $options['random_time'] = '3600';
                    }

                    $randCron = (rand('0', $options['random_time']));
                    $queryPosted = "UPDATE products SET status = 'Posted' WHERE ID = '" . $itemID . "'";
                    $resultPosted = $mysqli->query($queryPosted) OR trigger_error($mysqli->error . "[$queryPosted]");
                    $queryUpdateWP = "UPDATE blogger SET lastpost = '" . $now . "' WHERE ID = '" . $bgID . "'";
                    $resultWP = $mysqli->query($queryUpdateWP) OR trigger_error($mysqli->error . "[$queryUpdateWP]");
                    $queryTask = "UPDATE scheduler SET last_fired = '" . $now . "', fire_time = '" . ($now + $cronjob + $randCron) . "' WHERE scriptpath like '%wp=" . $bgID . "'";
                    $resultTask = $mysqli->query($queryTask) OR trigger_error($mysqli->error . "[$queryTask]");
                    echo '<p><strong style="color:green">Posted:</strong> <a href="' . $bgPoster['1'] . '" target="_blank">' . $bgPoster['1'] . '</a></p>';
                } else {
                    echo '<p><span class="label label-danger">Error</span> Product ID: ' . $item_productId . '</p>';
                }
            }
        }
    } else {
        if (isset($_REQUEST['wp'])) {
            $queryNoitem = "INSERT INTO error_log (ID, type, blogId, blog_url, reason, datetime) VALUES (NULL, 'wordpress', '" . $wpID . "', '" . $wp_blogurl . "', 'No products', '" . $now . "')";
            $resultNoitem = $mysqli->query($queryNoitem) OR trigger_error($mysqli->error . "[$queryNoitem]");
            echo 'No products';
        }
        
        if (isset($_REQUEST['bg'])) {
            $queryNoitem = "INSERT INTO error_log (ID, type, blogId, blog_url, reason, datetime) VALUES (NULL, 'blogger', '" . $bgID . "', '" . $bg_blogurl . "', 'No products', '" . $now . "')";
            $resultNoitem = $mysqli->query($queryNoitem) OR trigger_error($mysqli->error . "[$queryNoitem]");
                echo 'No products';
        }
    }
}

?>
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <div class="form-group">
                                                <button type="button" class="btn white" onClick="history.go(-1);return true;">Back</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end: CONTENT BODY -->

