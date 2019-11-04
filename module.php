<?php

if (isset($_GET['module'])) {
    $module['module'] = $_GET['module'];
    if (isset($_GET['m-option'])) {
        $module['m-option'] = $_GET['m-option'];
    }

    if ($module['module'] == 'dashboard') {
        $module['file'] = 'dashboard.php';
        $module['title'] = 'Dashboard';
    }

    /* Login */
    if ($module['module'] == 'login') {
        $module['file'] = 'module/authentication/authentication.php';
        $module['title'] = 'Login';
    }

    /* Logout */
    if ($module['module'] == 'logout') {
        $module['file'] = 'module/authentication/authentication.php';
        $module['title'] = 'Logout';
    }

    /* Wordpress Projects */
    if ($module['module'] == 'wordpress') {
        if (isset($_GET['m-option'])) {
            if ($_GET['m-option'] == 'add-wordpress') {
                $module['file'] = 'module/wordpress/add-wordpress.php';
                $module['title'] = 'Add Wordpress';
            }

            if ($_GET['m-option'] == 'edit-wordpress') {
                $module['file'] = 'module/wordpress/edit-wordpress.php';
                $module['title'] = 'Edit Wordpress';
            }

            if ($_GET['m-option'] == 'edit-keyword') {
                $module['file'] = 'module/wordpress/edit-keyword.php';
                $module['title'] = 'Edit Keyword';
            }

        } else {
            $module['file'] = 'module/wordpress/wordpress.php';
            $module['title'] = 'Wordpress Projects';
        }
    }

    /* Blogger Projects */
    if ($module['module'] == 'blogger') {
        if (isset($_GET['m-option'])) {
            if ($_GET['m-option'] == 'add-blogger') {
                $module['file'] = 'module/blogger/add-blogger.php';
                $module['title'] = 'Add Blogger';
            }

            if ($_GET['m-option'] == 'edit-blogger') {
                $module['file'] = 'module/blogger/edit-blogger.php';
                $module['title'] = 'Edit Blogger';
            }

            if ($_GET['m-option'] == 'edit-keyword') {
                $module['file'] = 'module/blogger/edit-keyword.php';
                $module['title'] = 'Edit Keyword';
            }
        } else {
            $module['file'] = 'module/blogger/blogger.php';
            $module['title'] = 'Blogger Projects';
        }
    }

    /* Quick Post */
    if ($module['module'] == 'wp-quick-post') {
        $module['file'] = 'module/wordpress/quick-post.php';
        $module['title'] = 'Wordpress Quick Post';
    }

    if ($module['module'] == 'bg-quick-post') {
        $module['file'] = 'module/blogger/quick-post.php';
        $module['title'] = 'Blogger Quick Post';
    }

    /* XML Generator */
    if ($module['module'] == 'wp-xml') {
        $module['file'] = 'module/xml/wordpress-xml.php';
        $module['title'] = 'Wordpress XML Generator';
    }

    if ($module['module'] == 'bg-xml') {
        $module['file'] = 'module/xml/blogger-xml.php';
        $module['title'] = 'Blogger XML Generator';
    }


    /* Template */
    if ($module['module'] == 'template') {
        if (isset($_GET['m-option'])) {
            if ($_GET['m-option'] == 'add-template') {
                $module['file'] = 'module/template/add-template.php';
                $module['title'] = 'Add Template';
            }

            if ($_GET['m-option'] == 'edit-template') {
                $module['file'] = 'module/template/edit-template.php';
                $module['title'] = 'Edit Template';
            }
        } else {
            $module['file'] = 'module/template/template.php';
            $module['title'] = 'Template Management';
        }
    }

    /* Random Header Content */
    if ($module['module'] == 'header-contents' || $module['module'] == 'footer-contents') {
        if ($module['module'] == 'header-contents') {
            $randTitle = 'Header';
        }

        if ($module['module'] == 'footer-contents') {
            $randTitle = 'Footer';
        }

        if (isset($_GET['m-option'])) {
            if ($_GET['m-option'] == 'add') {
                $module['file'] = 'module/random-content/add-content.php';
                $module['title'] = 'Add Random ' . $randTitle . ' Header Content';
            }
            
            if ($_GET['m-option'] == 'edit') {
                $module['file'] = 'module/random-content/edit-content.php';
                $module['title'] = 'Edit Random ' . $randTitle . ' Content';
            }
        } else {
            $module['file'] = 'module/random-content/random-contents.php';
            $module['title'] = 'Random ' . $randTitle . ' Content Management';
        }
    }

    /* Template */
    if ($module['module'] == 'preview') {
        $module['file'] = 'module/random-content/preview.php';
        $module['title'] = 'Preview';
    }

    /* Scheduled Task */
    if ($module['module'] == 'scheduled-task') {
        $module['file'] = 'module/scheduled-task/scheduled-task.php';
        $module['title'] = 'Scheduled Task';
    }

    /* Error Log */
    if ($module['module'] == 'error-log') {
        $module['file'] = 'module/error-log/error-log.php';
        $module['title'] = 'Error Log';
    }

    /* Poster */
    if ($module['module'] == 'poster') {
        $module['file'] = 'module/poster/poster.php';
        $module['title'] = 'Poster';
    }

    /* Settings */
    if ($module['module'] == 'options') {
        $module['file'] = 'options.php';
        $module['title'] = 'Options';
    }

} else {
    $module['module'] = 'dashboard';
    $module['file'] = 'dashboard.php';
    $module['title'] = 'Dashboard';
}

?>