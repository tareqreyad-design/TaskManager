<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="" class="nav-link">Home</a>
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


<!--         User Menu & Logout-->
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <img src="" class="user-image img-circle elevation-2" alt=" ">
                <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User' ); ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- User image -->
                <li class="user-header bg-primary">
                    <img src="" class="img-circle elevation-2" alt=" ">
                    <p>
                        <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
                        <small><?php echo $_SESSION['role'] ?? 'Member'; ?></small>
                    </p>
                </li>
                <!-- Menu Footer -->
                <li class="user-footer">
                    <a href="logout.php" class="btn btn-danger float-right">
                        <i class="fas fa-sign-out-alt"></i>Log OUT
                    </a>
                </li>
            </ul>
        </li>


    </ul>
</nav>

<?php
