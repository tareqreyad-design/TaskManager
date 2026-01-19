<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light"> Company Name </span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <li class="nav-item">

        <a href="profile.php" class="nav-link"  style="color: antiquewhite; font-weight: bold;">
            <i class="nav-icon fas fa-user-cog"></i>

            <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User' ); ?></span>
        </a>
        </li>

            <div class="brand-panel mt-3 pb-3 mb-3 d-flex align-items-cente">

<!--                 Company Logo-->
<!--                <div class="image">-->
<!--                <img src="#"-->
<!--                     alt="Company Logo"-->
<!--                     style="width:40px; height:40px; opacity:0.9;">-->
<!--                </div>-->

                <!-- Company Name -->
                <div class="info ml-2" style="color: antiquewhite; font-weight: normal;">
                    <span class="d-none d-md-inline">task name</span>
                </div>

            </div>
        <!-- SidebarSearch Form -->

        <div class="form-inline">
<!--            <i class="nav-icon fas fa-tachometer-alt"></i>-->
            <div class="use"> <a>Core Functionalities :</a> </div>

            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">

                </div>
            </div>
        </div>
        <!-- Sidebar Menu -->

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item has-treeview">
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>

                    <a href="#" class="nav-link">
                        <i class="av-icon fas fa-users-cog"></i>
                        <p> User Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="users_create.php" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create new users </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="users.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Edit users details </p>
                            </a>
                        </li>

                    </ul>
                </li>
                <?php endif; ?>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-project-diagram"></i>
                        <p>
                            Project Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="projects_create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create new projects  </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="projects.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Edit projects details </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>
                            Task Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="tasks_create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Create new tasks  </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="tasks.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Edit tasks details  </p>
                            </a>
                        </li>

                </li>

            </ul>

        </nav>
    </div>
    <!-- /.sidebar -->
</aside>
<?php
