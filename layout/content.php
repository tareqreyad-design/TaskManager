<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">

                <!-- Header -->
                <header>
                    <h2>Task Management System</h2>
                    <hr>
                </header>

                <!-- Section 1: Tasks grouped by status -->
<!--                <section>-->
<!--                    <h3>Tasks by Status</h3>-->
<!---->
<!--                    <table border="1" cellpadding="10" width="100%">-->
<!--                        <tr>-->
<!--                            <th>Status</th>-->
<!--                            <th>Value</th>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td>To Do</td>-->
<!--                            <td>4</td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td>In Progress</td>-->
<!--                            <td>3</td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td>Done</td>-->
<!--                            <td>7</td>-->
<!--                        </tr>-->
<!---->
<!--                    </table>-->
<!---->
<!--                </section> -->
                <br>
<!--                <section>-->
<!--                    <h3>Tasks by Priority</h3>-->
<!---->
<!--                    <table border="1" cellpadding="10" width="100%">-->
<!--                        <tr>-->
<!--                            <th>Priority</th>-->
<!--                            <th>condition</th>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td>Low</td>-->
<!--                            <td>---</td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td>High</td>-->
<!--                            <td>---</td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td>Done</td>-->
<!--                            <td>---</td>-->
<!--                        </tr>-->
<!--                    </table>-->
<!--                </section> -->
                <br>
                  <!-- Section 2: Overdue Tasks -->
<!--                <section>-->
<!--                    <h3>Overdue Tasks</h3>-->
<!---->
<!--                    <table border="1" cellpadding="10" width="100%">-->
<!--                        <tr>-->
<!--                            <th>Task Title</th>-->
<!--                            <th>Project</th>-->
<!--                            <th>Due Date</th>-->
<!--                            <th>Assigned User</th>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td>Design Dashboard</td>-->
<!--                            <td>Website Project</td>-->
<!--                            <td>2025-12-15</td>-->
<!--                            <td>tareq</td>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td>php page</td>-->
<!--                            <td>Sectors System</td>-->
<!--                            <td>2026-1-10</td>-->
<!--                            <td>omar</td>-->
<!--                        </tr>-->
<!--                    </table>-->
<!--                </section>  -->


                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">

                        <!-- العنوان الرئيسي زي الـ PDF -->
                        <!-- Tasks grouped by status - جدول بسيط -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Number of tasks grouped by status</h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Status</th>
                                                <th>Number of Tasks</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>To Do</td>
                                                <td class="text-info font-weight-bold"><?php echo $tasksByStatus['To Do'] ?? 0; ?></td>
                                            </tr>
                                            <tr>
                                                <td>In Progress</td>
                                                <td class="text-warning font-weight-bold"><?php echo $tasksByStatus['In Progress'] ?? 0; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Done</td>
                                                <td class="text-success font-weight-bold"><?php echo $tasksByStatus['Done'] ?? 0; ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Overdue Tasks -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card card-danger">
                                    <div class="card-header">
                                        <h3 class="card-title">Overdue tasks (due date < today and not completed)</h3>
                                    </div>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Task Title</th>
                                                <th>Project</th>
                                                <th>Due Date</th>
                                                <th>Assigned User</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if (empty($overdueTasks)): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-success">لا توجد مهام متأخرة حاليًا</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($overdueTasks as $task): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                                                        <td><?php echo htmlspecialchars($task['project_name'] ?? 'غير محدد'); ?></td>
                                                        <td><span class="badge badge-danger"><?php echo htmlspecialchars($task['due_date']); ?></span></td>
                                                        <td><?php echo htmlspecialchars($task['assigned_username'] ?? 'غير معين'); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tasks assigned to the currently logged-in user -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">Tasks assigned to the currently logged-in user</h3>
                                    </div>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Task Title</th>
                                                <th>Project</th>
                                                <th>Status</th>
                                                <th>Priority</th>
                                                <th>Due Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if (empty($assignedTasks)): ?>
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">لا توجد مهام معينة لك حاليًا</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($assignedTasks as $task): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                                                        <td><?php echo htmlspecialchars($task['project_name'] ?? 'غير محدد'); ?></td>
                                                        <td>
                                                <span class="badge badge-<?php echo $task['status'] == 'Done' ? 'success' : ($task['status'] == 'In Progress' ? 'warning' : 'info'); ?>">
                                                    <?php echo htmlspecialchars($task['status']); ?>
                                                </span>
                                                        </td>
                                                        <td>
                                                <span class="badge badge-<?php echo $task['priority'] == 'High' ? 'danger' : ($task['priority'] == 'Medium' ? 'warning' : 'secondary'); ?>">
                                                    <?php echo htmlspecialchars($task['priority']); ?>
                                                </span>
                                                        </td>
                                                        <td><?php echo $task['due_date'] ? htmlspecialchars($task['due_date']) : '-'; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>


            </div>

        </div>

    </div>

</div>

<?php
