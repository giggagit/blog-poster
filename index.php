<?php

session_start();

require_once 'functions.php';
require_once 'setting.php';
require_once 'module.php';

if ($module['module'] == 'login' || $module['module'] == 'logout') {
    //
} else {
    if (!isset($_SESSION['userid'])) {
        header("location: ?module=login");
        exit();
    }
}

?>
<!DOCTYPE HTML>
<html>
    <!-- start: HEAD -->
    <?php include 'header.php'; ?>
    <!-- end: HEAD -->
    <!-- start: BODY -->
    <body>
        <!-- start: NAVIGATION BAR -->
        <?php include 'navbar.php'; ?>
        <!-- end: NAVIGATION BAR -->
        <?php
        if ($module['module'] == 'login' || $module['module'] == 'logout') {
            include $module['file'];
        } else { ?>
        <!-- start: PAGE CONTENT -->
        <div class="page-content">
            <div class="container">
                <!-- start: SIDEBAR -->
                <?php include 'sidebar.php'; ?>
                <!-- end: SIDEBAR -->
                <!-- start: MAIN CONTENT -->
                <div class="main-content">
                    <!-- start: PAGE TITLE -->
                    <!-- <div class="page-title">
                        <h2><i class="fa fa-desktop color"></i> <?php //echo $module['title']; ?></h2>
                        <hr />
                    </div> -->
                    <!-- end: PAGE TITLE -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- start: CONTENT BOX -->
                            <?php include $module['file']; ?>
                            <!-- end: CONTENT BOX -->
                            <?php
                            echo "<br>";
                            echo "<pre> _POST <br>";
                            print_r($_POST);
                            echo "</pre>";
                            echo "<pre> _GET <br>";
                            print_r($_GET);
                            echo "</pre>";
                            ?>
                        </div>
                    </div>
                </div>
                <!-- end: MAIN CONTENT -->
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- end: PAGE CONTENT -->
        <?php } ?>
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
<?php $mysqli->close(); ?>

