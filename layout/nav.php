<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="dashbord.php" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </li>


        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user-circle fa-2x"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">
                        مرحباً،

                        <br>
                        <img src="" class="user-image img-circle elevation-2" alt=" ">
                        <?php echo $_SESSION['username'] ?? 'User'; ?></span>

                    <div class="dropdown-divider"></div>

                    <a href="profile.php" class="btn btn-block">
                        <i class="fas fa-user mr-2"></i> الملف الشخصي
                    </a>

                    <div class="dropdown-divider"></div>

                    <a href="logout.php" class="btn btn-danger ">
                        <i class="fas fa-sign-out-alt mr-2"></i> تسجيل الخروج
                    </a>
                </div>
            </li>

        </ul>

<!--         User Menu & Logout-->
<!--        <li class="nav-item dropdown user-menu">-->
<!--            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">-->
<!--                <img src="" class="user-image img-circle elevation-2" alt=" ">-->
<!--                <span class="d-none d-md-inline">--><?php //echo htmlspecialchars($_SESSION['username'] ?? 'User' ); ?><!--</span>-->
<!--            </a>-->
<!--            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">-->
                <!-- User image -->
<!--                <li class="user-header bg-primary">-->
<!--                    <img src="" class="img-circle elevation-2" alt=" ">-->
<!--                    <p>-->
<!--                        --><?php //echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
<!--                        <small>--><?php //echo $_SESSION['role'] ?? 'Member'; ?><!--</small>-->
<!--                    </p>-->
<!--                </li>-->
                <!-- Menu Footer -->
<!--                <li class="user-footer">-->
<!--                    <a href="logout.php" class="btn btn-danger float-right">-->
<!--                        <i class="fas fa-sign-out-alt"></i>Log OUT-->
<!--                    </a>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </li>-->


    </ul>
</nav>

<?php
