<?php

$tempID = slashes($_GET['id']);
$queryTemplate = "SELECT * FROM template WHERE ID = '" . $tempID . "'";
$resultTemplate = $mysqli->query($queryTemplate)->fetch_array(MYSQLI_ASSOC) OR trigger_error($mysqli->error . "[$queryTemplate]");
$getOptions = array_filter(explode( ', ', $resultTemplate['options']));
if ($resultTemplate['compare_table'] == 'ON') {
    $compareOn = 'checked';
} else {
    $compareOn = '';
}

if ($resultTemplate['compare_table'] == 'OFF') {
    $compareOff = 'checked';
} else {
    $compareOff = '';
}

?>
                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-desktop color"></i> <?php echo $module['title']; ?></h2>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <div class="content-body">
                                    <form name="edit-template" class="form-horizontal" id="template" data-toggle="validator" action="?module=template" method="POST">
                                        <input name="action" type="hidden" value="edit" />
                                        <input type="hidden" name="tempID" value="<?php echo $tempID; ?>" />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Template Name:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="template-name" class="form-control" placeholder="Template Name" value="<?php echo $resultTemplate['name']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Post Title:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="post-title" class="form-control" placeholder="Post Title" value="<?php echo $resultTemplate['title']; ?>" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Prefix:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="prefix" class="form-control" placeholder="Prefix" value="<?php echo $resultTemplate['prefix']; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Suffix:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="suffix" class="form-control" placeholder="Suffix" value="<?php echo $resultTemplate['suffix']; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Comparison:</label>
                                                    <div class="col-md-10">
                                                        <div class="radio radio-inline">
                                                            <label>
                                                                <input type="radio" name="compare-table" value="ON" <?php echo $compareOn; ?>>ON
                                                            </label>
                                                        </div>
                                                        <div class="radio radio-inline">
                                                            <label>
                                                                <input type="radio" name="compare-table" value="OFF" <?php echo $compareOff; ?>>OFF
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Post Template:</label>
                                                    <div class="col-md-10">
                                                        <textarea name="post-template" class="form-control summernote"><?php echo $resultTemplate['post_template']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h3>Options</h3>
                                        <fieldset id="options">
                                        <?php
                                        if (isset($getOptions)) {
                                            $i = '1';
                                            foreach ($getOptions as $tempOptions) {
                                                $tempOptions = explode('|', $tempOptions);
                                        ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label">Option Name</label>
                                                        <div class="col-md-3">
                                                            <input type="text" name="" class="form-control" id="optionName-<?php echo $i; ?>" placeholder="Option name" value="<?php echo $tempOptions['0']; ?>" required />
                                                        </div>
                                                        <label class="col-md-2 control-label">Option Value</label>
                                                        <div class="col-md-3">
                                                            <input type="text" name="options[<?php echo $tempOptions['0']; ?>]" class="form-control" id="optionValue-<?php echo $i; ?>" placeholder="Option Value" value="<?php echo $tempOptions['1']; ?>" required />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-xs red remove"><i class="fa fa-times"></i> Remove Option</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $i++; }
                                        } ?>
                                        </fieldset>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label"></label>
                                                    <div class="col-md-10">
                                                        <input type="button" class="form-control btn black more-option" value="Add More Options" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group pull-right">
                                                    <button type="submit" class="btn blue submit" disabled>Submit</button>
                                                    <button type="button" class="btn yellow preview">Preview</button>
                                                    <button type="button" class="btn white" onClick="history.go(-1);return true;">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- end: CONTENT BODY -->
                            </div>

