
                <div class="sidebar-dropdown"><a href="#">MENU</a></div>
                <div class="sidebar">
                    <ul class="nav">
                        <li <?php if ($module['module'] == 'dashboard') { echo 'class="current"'; } ?>><a href="?module=dashboard"><i class="fa fa-home"></i> Dashboard</a></li>
                        <li class="sub-menu <?php if (($module['module'] == 'wordpress') || ($module['module'] == 'wp-quick-post')  || ($module['module'] == 'wp-xml')) { echo 'current open'; } ?>">
                            <a href="#">
                            <i class="fa fa-sitemap"></i> Wordpress
                            <span class="caret pull-right"></span>
                            </a>
                            <ul>
                                <li <?php if ($module['module'] == 'wordpress') { echo 'class="active"'; } ?>><a href="?module=wordpress">Management</a></li>
                                <li <?php if ($module['module'] == 'wp-quick-post') { echo 'class="active"'; } ?>><a href="?module=wp-quick-post">Quick Post</a></li>
                                <li <?php if ($module['module'] == 'wp-xml') { echo 'class="active"'; } ?>><a href="?module=wp-xml">XML Generator</a></li>
                            </ul>
                        </li>
                        <li class="sub-menu <?php if (($module['module'] == 'blogger') || ($module['module'] == 'bg-quick-post') || ($module['module'] == 'bg-xml')) { echo 'current open'; } ?>">
                            <a href="#">
                            <i class="fa fa-th"></i> Blogger
                            <span class="caret pull-right"></span>
                            </a>
                            <ul>
                                <li <?php if ($module['module'] == 'blogger') { echo 'class="active"'; } ?>><a href="?module=blogger">Management</a></li>
                                <li <?php if ($module['module'] == 'bg-quick-post') { echo 'class="active"'; } ?>><a href="?module=bg-quick-post">Quick Post</a></li>
                                <li <?php if ($module['module'] == 'bg-xml') { echo 'class="active"'; } ?>><a href="?module=bg-xml">XML Generator</a></li>
                            </ul>
                        </li>
                        <li <?php if ($module['module'] == 'template') { echo 'class="current"'; } ?>><a href="?module=template"><i class="fa fa-file"></i> Template</a></li>
                        <li class="sub-menu <?php if (($module['module'] == 'header-contents') || ($module['module'] == 'footer-contents')) { echo 'current open'; } ?>">
                            <a href="#">
                            <i class="fa fa-file-text-o"></i> Random Contents
                            <span class="caret pull-right"></span>
                            </a>
                            <ul>
                                <li <?php if ($module['module'] == 'header-contents') { echo 'class="active"'; } ?>><a href="?module=header-contents">Header Contents</a></li>
                                <li <?php if ($module['module'] == 'footer-contents') { echo 'class="active"'; } ?>><a href="?module=footer-contents">Footer Contents</a></li>
                            </ul>
                        </li>
                        <li <?php if ($module['module'] == 'scheduled-task') { echo 'class="current"'; } ?>><a href="?module=scheduled-task"><i class="fa fa-calendar"></i> Scheduled Task</a></li>
                        <li <?php if ($module['module'] == 'error-log') { echo 'class="active"'; } ?>><a href="?module=error-log"><i class="fa fa-bug"></i> Error Log</a></li>
                        <li <?php if ($module['module'] == 'options') { echo 'class="active"'; } ?>><a href="?module=options"><i class="fa fa-cog"></i> Options</a></li>
                        <li <?php if ($module['module'] == 'logout') { echo 'class="active"'; } ?>><a href="?module=logout"><i class="fa fa-unlock-alt"></i> Logout</a></li>
                    </ul>
                </div>

