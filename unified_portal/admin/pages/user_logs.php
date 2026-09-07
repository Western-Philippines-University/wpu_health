<?php
/**
 * Modular page: user_logs
 * Included by wpu_page_router.php — do not access directly.
 */
                            $logs_page = isset($_GET['logs_page']) ? max(1, (int) $_GET['logs_page']) : 1;
                            $logs_per_page = 15;
                            $logs_user_filter = isset($_GET['logs_user']) ? trim((string) $_GET['logs_user']) : '';

                            $has_activity_logs = false;
                            try {
                                $chk = $pdo->query("SHOW TABLES LIKE 'activity_logs'");
                                $has_activity_logs = $chk && $chk->rowCount() > 0;
                            } catch (Throwable $e) {
                                $has_activity_logs = false;
                            }

                            $logs_user_options = [];
                            try {
                                foreach ($pdo->query('SELECT username FROM admins ORDER BY username')->fetchAll(PDO::FETCH_COLUMN) as $un) {
                                    $logs_user_options[(string) $un] = true;
                                }
                            } catch (Throwable $e) {
                            }
                            try {
                                foreach ($pdo->query('SELECT DISTINCT username FROM user_logs ORDER BY username')->fetchAll(PDO::FETCH_COLUMN) as $un) {
                                    $logs_user_options[(string) $un] = true;
                                }
                            } catch (Throwable $e) {
                            }
                            if ($has_activity_logs) {
                                try {
                                    foreach ($pdo->query('SELECT DISTINCT username FROM activity_logs ORDER BY username')->fetchAll(PDO::FETCH_COLUMN) as $un) {
                                        $logs_user_options[(string) $un] = true;
                                    }
                                } catch (Throwable $e) {
                                }
                            }
                            $logs_user_options = array_keys($logs_user_options);
                            natcasesort($logs_user_options);
                            $logs_user_options = array_values($logs_user_options);

                            if ($logs_user_filter !== '' && ! in_array($logs_user_filter, $logs_user_options, true)) {
                                $logs_user_filter = '';
                            }

                            $filter_ul = '';
                            $filter_al = '';
                            $count_bind = [];
                            if ($logs_user_filter !== '') {
                                $filter_ul = ' AND ul.username = ?';
                                $filter_al = ' AND al.username = ?';
                                $count_bind[] = $logs_user_filter;
                                $count_bind[] = $logs_user_filter;
                            }

                            if ($has_activity_logs) {
                                $logs_count_sql = "SELECT COUNT(*) FROM (
                                    SELECT ul.id FROM user_logs ul WHERE 1=1{$filter_ul}
                                    UNION ALL
                                    SELECT al.id FROM activity_logs al WHERE 1=1{$filter_al}
                                ) t";
                            } else {
                                $logs_count_sql = "SELECT COUNT(*) FROM user_logs ul WHERE 1=1{$filter_ul}";
                            }

                            $logs_count_stmt = $pdo->prepare($logs_count_sql);
                            $logs_count_stmt->execute($count_bind);
                            $logs_total = (int) $logs_count_stmt->fetchColumn();
                            $logs_total_pages = max(1, (int) ceil($logs_total / $logs_per_page));
                            if ($logs_page > $logs_total_pages) {
                                $logs_page = $logs_total_pages;
                            }
                            $logs_offset = ($logs_page - 1) * $logs_per_page;

                            $logs_rows = [];
                            if ($logs_total > 0) {
                                if ($has_activity_logs) {
                                    $logs_fetch_sql = "SELECT * FROM (
                                        SELECT
                                            ul.username AS username,
                                            'session' AS evt_type,
                                            TRIM(CONCAT(ul.activity_type, IF(ul.status IS NOT NULL AND ul.status != '', CONCAT(' — ', ul.status), ''))) AS evt_title,
                                            TRIM(CONCAT(
                                                IF(ul.logout_time IS NOT NULL, CONCAT('Logout ', DATE_FORMAT(ul.logout_time, '%b %e, %Y %h:%i %p')), ''),
                                                IF(ul.duration IS NOT NULL AND ul.duration != '', CONCAT(IF(ul.logout_time IS NOT NULL, ' · ', ''), 'Duration ', ul.duration), '')
                                            )) AS evt_detail,
                                            COALESCE(ul.login_time, ul.created_at) AS evt_at,
                                            ul.status AS session_status
                                        FROM user_logs ul
                                        WHERE 1=1{$filter_ul}
                                        UNION ALL
                                        SELECT
                                            al.username,
                                            'action',
                                            al.action,
                                            IFNULL(NULLIF(TRIM(al.details), ''), '—'),
                                            al.created_at,
                                            NULL
                                        FROM activity_logs al
                                        WHERE 1=1{$filter_al}
                                    ) merged
                                    ORDER BY merged.evt_at DESC
                                    LIMIT ? OFFSET ?";
                                } else {
                                    $logs_fetch_sql = "SELECT
                                            ul.username AS username,
                                            'session' AS evt_type,
                                            TRIM(CONCAT(ul.activity_type, IF(ul.status IS NOT NULL AND ul.status != '', CONCAT(' — ', ul.status), ''))) AS evt_title,
                                            TRIM(CONCAT(
                                                IF(ul.logout_time IS NOT NULL, CONCAT('Logout ', DATE_FORMAT(ul.logout_time, '%b %e, %Y %h:%i %p')), ''),
                                                IF(ul.duration IS NOT NULL AND ul.duration != '', CONCAT(IF(ul.logout_time IS NOT NULL, ' · ', ''), 'Duration ', ul.duration), '')
                                            )) AS evt_detail,
                                            COALESCE(ul.login_time, ul.created_at) AS evt_at,
                                            ul.status AS session_status
                                        FROM user_logs ul
                                        WHERE 1=1{$filter_ul}
                                        ORDER BY evt_at DESC
                                        LIMIT ? OFFSET ?";
                                }
                                $fetch_bind = $count_bind;
                                $fetch_bind[] = $logs_per_page;
                                $fetch_bind[] = $logs_offset;
                                $logs_stmt = $pdo->prepare($logs_fetch_sql);
                                $logs_stmt->execute($fetch_bind);
                                $logs_rows = $logs_stmt->fetchAll(PDO::FETCH_ASSOC);
                            }
                            ?>
                            <div class="content-card">
                                <div class="card-header">
                                    <h2 class="card-title">
                                        <i class="fas fa-history" aria-hidden="true"></i>
                                        User activity log
                                    </h2>
                                </div>
                                <div class="card-body">
                                    <div class="logs-page">
                                        <p class="settings-intro">
                                            <strong>Per-user activity</strong> — sign-in sessions from <code>user_logs</code> and admin actions (uploads, deletes, settings, etc.) from <code>activity_logs</code>, newest first. Use the filter to view one account.
                                        </p>
                                        <?php if (! $has_activity_logs): ?>
                                        <p class="settings-intro" style="border-left: 4px solid #f59e0b;">
                                            The <code>activity_logs</code> table is not in this database yet. Run a fresh <code>wpu_unified.sql</code> import or execute <code>unified_portal/database/add_activity_logs.sql</code> (or <code>php unified_portal/database/run_add_activity_logs.php</code>) so action entries are stored and shown.
                                        </p>
                                        <?php endif; ?>

                                        <form method="get" class="logs-filter-bar" action="">
                                            <input type="hidden" name="page" value="user_logs">
                                            <label for="logs_user_select" class="logs-filter-label">User</label>
                                            <select name="logs_user" id="logs_user_select" class="form-control logs-filter-select">
                                                <option value="">All users</option>
                                                <?php foreach ($logs_user_options as $opt_user): ?>
                                                    <option value="<?php echo htmlspecialchars($opt_user, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $logs_user_filter === $opt_user ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($opt_user, ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                                            <?php if ($logs_user_filter !== ''): ?>
                                                <a class="btn btn-sm" href="?page=user_logs">Clear filter</a>
                                            <?php endif; ?>
                                        </form>

                                        <div class="logs-table-wrap">
                                            <div class="table-responsive">
                                                <table class="logs-table">
                                                    <caption class="visually-hidden">User activity, newest first</caption>
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Username</th>
                                                            <th scope="col">Type</th>
                                                            <th scope="col">Activity</th>
                                                            <th scope="col">Details</th>
                                                            <th scope="col">When</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (! empty($logs_rows)): ?>
                                                            <?php foreach ($logs_rows as $log): ?>
                                                                <?php
                                                                $evt_type = (string) ($log['evt_type'] ?? '');
                                                                $session_status = (string) ($log['session_status'] ?? '');
                                                                if ($evt_type === 'action') {
                                                                    $type_class = 'info';
                                                                    $type_label = 'Action';
                                                                    $badge_class = 'info';
                                                                } else {
                                                                    $type_class = 'neutral';
                                                                    $type_label = 'Session';
                                                                    if ($session_status === 'Logged In') {
                                                                        $badge_class = 'success';
                                                                    } elseif ($session_status === 'Logged Out') {
                                                                        $badge_class = 'neutral';
                                                                    } else {
                                                                        $badge_class = 'info';
                                                                    }
                                                                }
                                                                $evt_ts = strtotime((string) ($log['evt_at'] ?? ''));
                                                                $detail_show = trim((string) ($log['evt_detail'] ?? ''));
                                                                if ($detail_show === '') {
                                                                    $detail_show = '—';
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td><span class="log-user"><?php echo htmlspecialchars((string) ($log['username'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span></td>
                                                                    <td><span class="status-badge <?php echo htmlspecialchars($type_class, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($type_label, ENT_QUOTES, 'UTF-8'); ?></span></td>
                                                                    <td>
                                                                        <span class="log-activity"><?php echo htmlspecialchars((string) ($log['evt_title'] ?? '—'), ENT_QUOTES, 'UTF-8'); ?></span>
                                                                        <?php if ($evt_type === 'session' && $session_status !== ''): ?>
                                                                            <br><span class="status-badge <?php echo htmlspecialchars($badge_class, ENT_QUOTES, 'UTF-8'); ?>" style="margin-top:6px;display:inline-block;"><?php echo htmlspecialchars($session_status, ENT_QUOTES, 'UTF-8'); ?></span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td><span class="log-activity"><?php echo htmlspecialchars($detail_show, ENT_QUOTES, 'UTF-8'); ?></span></td>
                                                                    <td><span class="log-time"><?php echo $evt_ts ? htmlspecialchars(date('M j, Y g:i A', $evt_ts), ENT_QUOTES, 'UTF-8') : htmlspecialchars((string) ($log['evt_at'] ?? '—'), ENT_QUOTES, 'UTF-8'); ?></span></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="5">
                                                                    <div class="table-empty" role="status">
                                                                        <span class="table-empty__icon" aria-hidden="true"><i class="fas fa-inbox"></i></span>
                                                                        <p class="table-empty__title">No activity yet</p>
                                                                        <p class="table-empty__hint"><?php echo $logs_user_filter !== '' ? 'No entries for this user. Try clearing the filter.' : 'Sign-ins and logged actions will appear here as staff use the admin panel.'; ?></p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <?php if ($logs_total > 0): ?>
                                        <div class="pagination">
                                            <div class="pagination-info">
                                                Showing <?php echo (int) ($logs_offset + 1); ?> to <?php echo (int) min($logs_offset + $logs_per_page, $logs_total); ?> of <?php echo (int) $logs_total; ?> entries
                                            </div>
                                            <?php if ($logs_total_pages > 1): ?>
                                            <div class="pagination-controls">
                                                <button type="button" <?php echo $logs_page <= 1 ? 'disabled' : ''; ?> onclick="changePage('user_logs', <?php echo (int) ($logs_page - 1); ?>)">
                                                    <i class="fas fa-chevron-left" aria-hidden="true"></i> Previous
                                                </button>
                                                <span class="pagination-page-label">
                                                    Page <?php echo (int) $logs_page; ?> of <?php echo (int) $logs_total_pages; ?>
                                                </span>
                                                <button type="button" <?php echo $logs_page >= $logs_total_pages ? 'disabled' : ''; ?> onclick="changePage('user_logs', <?php echo (int) ($logs_page + 1); ?>)">
                                                    Next <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
