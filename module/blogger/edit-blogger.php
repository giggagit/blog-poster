<?php

$bgID = slashes($_GET['id']);
$queryWP = "SELECT * FROM blogger WHERE ID = '" . $bgID . "'";
$resultBG = $mysqli->query($queryWP)->fetch_array(MYSQLI_ASSOC) OR trigger_error($mysqli->error . "[$queryWP]");
$queryKeywords = "SELECT * FROM keywords WHERE type = 'blogger' AND blogId = '" . $bgID ."'";
$resultKeywords = $mysqli->query($queryKeywords) OR trigger_error($mysqli->error . "[$queryKeywords]");
$queryTemplate = "SELECT * FROM template";
$resultTemplate = $mysqli->query($queryTemplate) OR trigger_error($mysqli->error . "[$queryTemplate]");
$queryTotal = "SELECT * FROM products";
$resultTotal = $mysqli->query($queryTotal)->num_rows OR trigger_error($mysqli->error . "[$queryTotal]");
$queryTotal .= " WHERE status ='Posted'";
$resultPosted = $mysqli->query($queryTotal)->num_rows OR trigger_error($mysqli->error . "[$queryTotal]");

?>

                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-desktop color"></i> <?php echo $module['title']; ?></h2>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <div class="content-body">
                                    <form name="add-blogger" class="form-horizontal" data-toggle="validator" action="?module=blogger" method="POST">
                                        <input type="hidden" name="action" value="edit" />
                                        <input type="hidden" name="bgID" value="<?php echo $bgID; ?>" />
                                        <h3>Blog Info</h3>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Blog URL:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="blogurl" class="form-control" placeholder="http://www.example.com" value="<?php echo $resultBG['blog_url']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Blog ID:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="blogid" class="form-control" placeholder="Blogger ID" value="<?php echo $resultBG['blogId']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Email:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="email" class="form-control" placeholder="Username" value="<?php echo $resultBG['email']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Password:</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="password" class="form-control" placeholder="Password" value="<?php echo $resultBG['password']; ?>" required />
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
                                                        <input type="text" name="api" class="form-control" placeholder="API (Optional)" value="<?php echo $resultBG['api']; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-2 control-label">Products:</label>
                                            <div class="col-md-10">
                                                <a href="#" class="col-md-12 btn green">Add Keywords</a>
                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-md-3">Keywords</th>
                                                            <th class="col-md-3">Categories</th>
                                                            <th class="col-md-2">Total</th>
                                                            <th class="col-md-2">Posted</th>
                                                            <th class="col-md-2">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($bgKeywords = $resultKeywords->fetch_array(MYSQLI_ASSOC)) { ?>
                                                        <tr>
                                                            <td><?php echo $bgKeywords['keywords']; ?></td>
                                                            <td><?php echo $bgKeywords['category']; ?></td>
                                                            <td>
                                                                <?php
                                                                $queryTotal = "SELECT * FROM products WHERE keywordId = '" . $bgKeywords['ID'] . "'";
                                                                $resultTotal = $mysqli->query($queryTotal)->num_rows OR trigger_error($mysqli->error . "[$queryTotal]");
                                                                echo $resultTotal;
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $queryTotal .= " AND status ='Posted'";
                                                                $resultTotal = $mysqli->query($queryTotal)->num_rows OR trigger_error($mysqli->error . "[$queryTotal]");
                                                                echo $resultTotal;
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <a href="?module=blogger&m-option=edit-keyword&id=<?php echo $bgKeywords['ID']; ?>" class="btn btn-xs yellow" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>
                                                                <a href="?module=blogger&m-option=edit-blogger&id=1&delete-keyword=<?php echo $bgKeywords['ID']; ?>" class="btn btn-xs red"><i class="fa fa-times" data-toggle="tooltip" title="Delete"></i></a>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Template:</label>
                                                    <div class="col-md-8">
                                                        <select name="template[]" class="form-control" multiple="multiple" required>
                                                            <?php while ($templateRow = $resultTemplate->fetch_array(MYSQLI_ASSOC)) { ?>
                                                            <option value="<?php echo $templateRow['ID']; ?>"<?php foreach (explode(', ', $resultBG['template']) as $templateID) { if ($templateID == $templateRow['ID']) { echo 'selected'; } } ?>><?php echo $templateRow['name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <input type="button" name="select-all" class="form-control btn black" value="Select All" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Duration:</label>
                                                    <div class="col-md-3">
                                                        <input type="text" name="duration" class="col-md-3 form-control" value="<?php echo $resultBG['duration']; ?>" required />
                                                    </div>
                                                    <div class="col-md-5">
                                                        <select name="time-format" class="form-control">
                                                        <option value="">Time Format</option>
                                                        <option value="60"<?php echo ($resultBG['timeformat'] == '60') ? " selected" : "" ?>>Minute</option>
                                                        <option value="3600"<?php echo ($resultBG['timeformat'] == '3600') ? " selected" : "" ?>>Hour</option>
                                                        <option value="86400"<?php echo ($resultBG['timeformat'] == '86400') ? " selected" : "" ?>>Day</option>
                                                        <option value="604800"<?php echo ($resultBG['timeformat'] == '604800') ? " selected" : "" ?>>Week</option>
                                                        <option value="2592000"<?php echo ($resultBG['timeformat'] == '2592000') ? " selected" : "" ?>>Month</option>
                                                        <option value="31536000"<?php echo ($resultBG['timeformat'] == '31536000') ? " selected" : "" ?>>Year</option>
                                                    </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group pull-right">
                                                    <button type="submit" class="btn blue" disabled>Submit</button>
                                                    <button type="reset" class="btn red">Reset</button>
                                                    <button type="button" class="btn white" onClick="history.go(-1);return true;">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- end: CONTENT BODY -->
                            </div>

