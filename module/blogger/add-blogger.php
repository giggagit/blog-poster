<?php

$queryTemplate = "SELECT * FROM template";
$resultTemplate = $mysqli->query($queryTemplate) OR trigger_error($mysqli->error . "[$queryTemplate]");

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
                                        <input type="hidden" name="action" value="add" />
                                        <h3>Blog Info</h3>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Blog URL:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="blogurl" class="form-control" placeholder="http://www.example.com" required />
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
                                                        <input type="text" name="email" class="form-control" placeholder="Email" required />
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
                                                    <label class="col-md-4 control-label">Template:</label>
                                                    <div class="col-md-8">
                                                        <select name="template[]" class="form-control" multiple="multiple" required>
                                                            <?php while ($templateRow = $resultTemplate->fetch_array(MYSQLI_ASSOC)) { ?>
                                                            <option value="<?php echo $templateRow['ID']; ?>"><?php echo $templateRow['name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <input type="button"t name="select-all" class="form-control btn black" value="Select All" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Duration:</label>
                                                    <div class="col-md-3">
                                                        <input type="text" name="duration" class="col-md-3 form-control" required />
                                                    </div>
                                                    <div class="col-md-5">
                                                        <select name="time-format" class="form-control">
                                                            <option value="">Time Format</option>
                                                            <option value="60">Minute</option>
                                                            <option value="3600">Hour</option>
                                                            <option value="86400">Day</option>
                                                            <option value="604800">Week</option>
                                                            <option value="2592000">Month</option>
                                                            <option value="31536000">Year</option>
                                                        </select>
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

