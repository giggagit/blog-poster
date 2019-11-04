<?php

if ($module['module'] == 'logout') {
    session_unset();
    session_destroy();
    header("location: ?module=login");
}

if ($module['module'] == 'login') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'login') {
            $authen = slashes($_POST);
            $username = $authen['username'];
            $password = hash("sha256", $authen['password']);
            if ($username == $options['username'] && $password == $options['password']) {
                $_SESSION['userid'] = session_id();
                header("location: ?module=dashboard");
            } else {
                $_POST['action'] = 'login-fail';
                $alertMsg = 'alert-danger';
                $loginMsg = '<p><span class="label label-danger">Error</span> User name or password is incorrect</p>';
            }
        }
    }
}

?>

        <div class="page-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <!-- start: CONTENT BOX -->
                        <div class="content-box login-body">
                            <!-- start: CONTENT HEAD -->
                            <div class="content-head text-center">
                                <h2><i class="fa fa-lock color"></i> <?php echo $module['title']; ?></h2>
                            </div>
                            <!-- end: CONTENT HEAD -->
                            <!-- start: CONTENT BODY -->
                            <div class="content-body">
                                <?php
                                if (isset($_POST['action'])) {
                                    if ($_POST['action'] == 'login-fail') { ?>
                                        <div class="alert <?php echo $alertMsg; ?>">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <?php echo $loginMsg; ?>
                                        </div>
                                    <?php }
                                }
                                ?>
                                <form name="login" class="form-horizontal" action="" method="POST">
                                    <input type="hidden" name="action" value="login" />
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Username</label>
                                        <div class="col-lg-10">
                                            <input type="text" name="username" class="form-control" placeholder="Username">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Password</label>
                                        <div class="col-lg-10">
                                            <input type="password" name="password" class="form-control" placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox"> Remember me
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="form-group">
                                        <div class="col-lg-offset-2 col-lg-10">
                                            <button type="submit" class="btn default">Sign in</button>
                                            <button type="reset" class="btn white">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- end: CONTENT BODY -->
                        </div>
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
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- end: PAGE CONTENT -->

