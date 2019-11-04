
                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h2><i class="fa fa-desktop color"></i> <?php echo $module['title']; ?></h2>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <div class="content-body">
                                    <form name="add-template" class="form-horizontal" id="template" data-toggle="validator" action="?module=template" method="POST">
                                        <input name="action" type="hidden" value="add" />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Template Name:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="template-name" class="form-control" placeholder="Template Name" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Post Title:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="post-title" class="form-control" placeholder="Post Title" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Prefix:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="prefix" class="form-control" placeholder="Prefix" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Suffix:</label>
                                                    <div class="col-md-10">
                                                        <input type="text" name="suffix" class="form-control" placeholder="Suffix" />
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
                                                                <input type="radio" name="compare-table" value="ON">ON
                                                            </label>
                                                        </div>
                                                        <div class="radio radio-inline">
                                                            <label>
                                                                <input type="radio" name="compare-table" value="OFF" checked>OFF
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
                                                        <textarea name="post-template" class="form-control summernote"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h3>Options</h3>
                                        <fieldset id="options">
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

