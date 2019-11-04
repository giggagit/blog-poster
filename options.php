<?php

if (isset($_POST['changep'])) {
    if ($_POST['changep'] == 'change') {
        $getPassword = slashes($_POST);
        if (hash("sha256", $getPassword['currentp']) == $options['password']) {
            if (!empty($getPassword['newp']) || !empty($getPassword['confirmp'])) {
                if ($getPassword['newp'] == $getPassword['confirmp']) {
                    $queryPassword = "UPDATE options SET option_value = '" . hash("sha256", $getPassword['newp']) . "' WHERE option_name = 'password'";
                    $resultPassword = $mysqli->query($queryPassword) OR trigger_error($mysqli->error . "[$queryPassword]");
                    $alertMsg = 'alert-success';
                    $optionsMsg = '<span class="label label-success">Success</span> Password updated';
                } else {
                    $alertMsg = 'alert-danger';
                    $optionsMsg = '<span class="label label-danger">Error</span> Password does not match the confirm password';
                }
            } else {
                $alertMsg = 'alert-danger';
                $optionsMsg = '<span class="label label-danger">Error</span> Please type a password, and then retype it to confirm';
            }
        } else {
            $alertMsg = 'alert-danger';
            $optionsMsg = '<span class="label label-danger">Error</span> Password does not match the old password';
        }
    }
}

if (isset($_POST['clear_cache'])) {
    if ($_POST['clear_cache'] == 'yes') {
        removeDir($options['cache_dir']);
        $alertMsg = 'alert-success';
        $optionsMsg = 'Clear cache complet';
    }
}

$cronURL = 'http'. ((isset($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"]) == "on") ? 's' : null) .'://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

?>
                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-cog color"></i> <?php echo $module['title']; ?></h2>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <div class="content-body">
                                    <?php
                                    if (isset($_POST['options'])) {
                                        if ($_POST['options'] == 'update') {
                                    ?>
                                    <div class="alert <?php echo $alertMsg; ?>">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <p><?php echo $optionsMsg; ?></p>
                                    </div>
                                        <?php }
                                    }
                                    if (isset($_POST['changep'])) {
                                        if ($_POST['changep'] == 'yes') {
                                    ?>
                                    <div class="alert <?php echo $alertMsg; ?>">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <p><?php echo $optionsMsg; ?></p>
                                    </div>
                                        <?php }
                                    }
                                    ?>
                                	<form name="options" class="form-horizontal" data-toggle="validator" action="?module=options" method="POST">
                                        <input type="hidden" name="options" value="update" />
                                        <h3>Login Detail</h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Username:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="username" class="form-control" placeholder="Username" value="<?php echo $options['username']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Password:</label>
                                                    <div class="col-md-8">
                                                        <a href="#password" class="col-md-12 btn red" data-toggle="modal"></i>Change Password</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h3>Site Preferences</h3>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Site URL:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="site_url" class="form-control" placeholder="http://www.example.com" value="<?php echo $options['site_url']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Primary API:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="primary_api" class="form-control" placeholder="API" value="<?php echo $options['primary_api']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Time Zone:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="time_zone" class="form-control" placeholder="Asia/Bangkok" value="<?php echo $options['time_zone']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Cache Dir:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="cache_dir" class="form-control" placeholder="/home/username/public_html/cache" value="<?php echo $options['cache_dir']; ?>" required />
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href="#cache" class="col-md-12 btn red" data-toggle="modal">Clear Cache</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Max Result:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="serp_limit" class="form-control" placeholder="Max search result" value="<?php echo $options['serp_limit']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Bulk Post:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="bulk_post" class="form-control" placeholder="Max bulk post" value="<?php echo $options['bulk_post']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Random Time:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="random_time" class="form-control" placeholder="Random time for posting (seconds)" value="<?php echo $options['random_time']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Random Date:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="random_date" class="form-control" placeholder="Random date for posting (days)" value="<?php echo $options['random_date']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Exclude Merchant:</label>
                                                    <div class="col-md-10">
                                                        <textarea rows="4" name="exclude_merchant" class="form-control" placeholder="Zappos.com (One per line)"><?php echo str_replace(", ", "\r\n", $options['exclude_merchant']); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h3>Cron Path</h3>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Localhost:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="localhost" class="form-control" value="<?php echo realpath(str_replace('index','cron', $_SERVER['SCRIPT_FILENAME'])); ?>" disabled />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">cPanel:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="cpanel" class="form-control" value="/usr/bin/php -q <?php echo str_replace('index','cron', $_SERVER['SCRIPT_FILENAME']); ?>" disabled />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">DirectAdmin:</label>
                                                    <div class="col-md-10"><p class="form-control-static">
                                                        <input type="text" name="direct-admin" class="form-control" value="wget -O /dev/null <?php echo str_replace("?module=options","cron.php", $cronURL); ?>" disabled />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Kloxo:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="kloxo" class="form-control" value="curl -O /dev/null <?php echo str_replace("?module=options","cron.php", $cronURL); ?>" disabled />
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
                                        <!-- start: #cache MODAL -->
                                        <div id="cache" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="cache" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h3 id="modal-title">Clear Cache</h3>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure that you want to permanently delete the cache files?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn white" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                                        <button type="submit" class="btn red" name="clear_cache" value="yes">Yes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end: #cache MODAL -->
                                    </form>
                                    <!-- start: #password MODAL -->
                                    <form name="options" class="form-horizontal" action="?module=options" method="POST">
                                        <div id="password" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="password" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        <h3 id="modal-title">Change Password</h3>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="col-md-4 control-label">Current Password:</label>
                                                                    <div class="col-md-8">
                                                                        <input type="password" name="currentp" class="form-control" placeholder="Current password" required />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="col-md-4 control-label">New Password:</label>
                                                                    <div class="col-md-8">
                                                                        <input type="password" name="newp" class="form-control" placeholder="New password" required />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="col-md-4 control-label">Confirm New Password:</label>
                                                                    <div class="col-md-8">
                                                                        <input type="password" name="confirmp" class="form-control" placeholder="Confirm new password" required />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn white" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                                        <button type="submit" class="btn red" name="changep" value="change" disabled>Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- end: #password MODAL -->
                                </div>
                                <!-- end: CONTENT BODY -->
                            </div>

