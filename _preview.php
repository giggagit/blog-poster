<!DOCTYPE html>
<html>
    <!-- start: HEAD -->
    <?php include 'header.php'; ?>
    <!-- end: HEAD -->
    <!-- start: BODY -->
    <body>
        <!-- start: NAVIGATION BAR -->
        <?php include 'navbar.php'; ?>
        <!-- end: NAVIGATION BAR -->
        <!-- start: PAGE CONTENT -->
        <div class="page-content">
            <div class="container">
                <!-- start: SIDEBAR -->
                <?php include 'sidebar.php'; ?>
                <!-- end: SIDEBAR -->
                <!-- start: MAIN CONTENT -->
                <div class="main-content">
                    <!-- start: PAGE TITLE -->
                    <div class="page-title">
                        <h2><i class="fa fa-desktop color"></i> Error Log <small>Subtext for header</small></h2>
                        <hr />
                    </div>
                    <!-- end: PAGE TITLE -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- start: CONTENT BOX -->
                            <div class="content-box">
                                <!-- start: CONTENT HEAD -->
                                <div class="content-head">
                                    <h3>Table #2</h3>
                                </div>
                                <!-- end: CONTENT HEAD -->
                                <!-- start: CONTENT BODY -->
                                <?php include 'file'; ?>
                                <!-- end: CONTENT BODY -->
                            </div>
                            <!-- end: CONTENT BOX -->
                        </div>
                    </div>
                </div>
                <!-- end: MAIN CONTENT -->
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- end: PAGE CONTENT -->
        <!-- start: FOOTER -->
        <?php include 'footer.php'; ?>
        <!-- end: FOOTER -->
        <!-- start: SCROLL TO TOP -->
        <span class="to-top"><a href="#"><i class="fa fa-chevron-up"></i></a></span>
        <!-- end: SCROLL TO TOP -->
        <!-- start: JAVASCRIPTS -->
        <?php include 'js-loader.php'; ?>
        <!-- end: JAVASCRIPTS -->
    </body>
</html>