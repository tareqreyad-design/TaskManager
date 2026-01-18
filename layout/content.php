

<div class="content">
    <div class="container-fluid">
        <div class="content-header">
            <h2 class="text-dark"><i class="fas fa-th-large mr-2"></i>Task Management System</h2>
            <hr>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-widget widget-user-2 shadow">
                    <div class="widget-user-header bg-navy">
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['username']); ?>&background=random" alt="User Avatar">
                        </div>
                        <h3 class="widget-user-username">أهلاً، <?php echo htmlspecialchars($_SESSION['username']);?> </h3>
                        <h5 class="widget-user-desc"><?php echo $_SESSION['role'] == 'Admin' ? 'مدير النظام (Admin)' : 'عضو فريق (Member)';?> </h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo $tasksByStatus['To Do'] ?? 0; ?></h3>
                        <p>مهام للقيام بها (To Do)</p>
                    </div>
                    <div class="icon"><i class="fas fa-list"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo $tasksByStatus['In Progress'] ?? 0; ?></h3>
                        <p>قيد التنفيذ (In Progress) </p>
                    </div>
                    <div class="icon"><i class="fas fa-spinner"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo $tasksByStatus['Done'] ?? 0; ?></h3>
                        <p>مهام مكتملة (Done) </p>
                    </div>
                    <div class="icon"><i class="fas fa-check-double"></i></div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?php echo count($overdueTasks); ?></h3>
                        <p>مهام متأخرة (Overdue)</p>
                    </div>
                    <div class="icon"><i class="fas fa-clock"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card card-danger card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title text-danger"><i class="fas fa-exclamation-circle mr-1"></i> Late tasks </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-striped">
                            <thead>
                            <tr>
                               <th>Tasks</th>
                                <th>Projects</th>
                                <th>Dates </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($overdueTasks)): ?>
                                <tr><td colspan="3" class="text-center py-3"> No tasks currently pending.ً</td></tr>
                            <?php else: foreach ($overdueTasks as $task): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($task['title']); ?></td>
                                    <td><?php echo htmlspecialchars($task['project_name']); ?></td>
                                    <td><span class="text-danger font-weight-bold"><?php echo $task['due_date']; ?></span></td>
                                </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title text-primary"><i class="fas fa-user-tag mr-1"></i> My assigned tasks </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-hover">
                            <thead>
                            <tr>
                               <th>Tasks </th>
                               <th>Priority </th>
                               <th>Condition </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($assignedTasks)): ?>
                                <tr><td colspan="3" class="text-center py-3"> No assigned tasks yet.</td></tr>
                            <?php else: foreach ($assignedTasks as $task): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($task['title']); ?></td>
                                    <td>
                                        <?php
                                        // التأكد من وجود القيمة أو وضع 'Low' كافتراضي
                                        $priority = $task['priority'] ?? 'Low';

                                        $p_class = $priority == 'High' ? 'danger' : ($priority == 'Medium' ? 'warning' : 'info');

                                        echo "<span class='badge badge-$p_class'>" . htmlspecialchars($priority) . "</span>";
                                        ?>
                                    </td>
                                    <td><span class="badge badge-light border"><?php echo $task['status']; ?></span></td>
                                </tr>
                            <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
