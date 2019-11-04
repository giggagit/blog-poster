<?php

$contentID = slashes($_GET['id']);
$queryContent = "SELECT * FROM random_contents WHERE ID = $contentID";
if ($module['module'] == 'header-contents') {
    $queryContent .= " AND type = 'header'";
}

if ($module['module'] == 'footer-contents') {
    $queryContent .= " AND type = 'footer'";
}

$resultContent = $mysqli->query($queryContent)->fetch_array(MYSQLI_ASSOC) OR trigger_error($mysqli->error . "[$queryContent]");

?>

                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-file-text-o color"></i> <?php echo $module['title']; ?></h2>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <div class="content-body">
                                    <form name="edit-<?php echo $module['module']; ?>-form" class="form-horizontal" action="?module=<?php echo $module['module']; ?>" method="POST">
                                        <input type="hidden" name="action" value="edit" />
                                        <input type="hidden" name="contentID" value="<?php echo $contentID; ?>" />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Content:</label>
                                                    <div class="col-md-10">
                                                        <textarea name="content" class="summernote"><?php echo $resultContent['contents']; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group pull-right">
                                                    <button type="submit" class="btn blue">Submit</button>
                                                    <button type="reset" class="btn red">Reset</button>
                                                    <button type="button" class="btn white" onClick="history.go(-1);return true;">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- end: CONTENT BODY -->
                            </div>

