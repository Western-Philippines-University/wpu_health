<?php
/**
 * Reports page view — uses cached department list from wpu_page_data.php.
 */
$report_today = date('Y-m-d');
$report_month = date('m');
$report_year = date('Y');
$reports_departments = $wpu_departments;
?>
<div class="content-card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-chart-bar" aria-hidden="true"></i>
            Reports &amp; analytics
        </h2>
    </div>
    <div class="card-body">
        <div class="reports-page">
            <p class="reports-intro">
                <strong>Print-ready summaries</strong> open in a new tab. Use your browser’s print dialog to save as PDF or send to a printer. <strong>Daily</strong> covers the current calendar day; <strong>monthly</strong> covers the current calendar month. Use the filters below for a different day or for one department.
            </p>
            <div class="analytics-row" style="margin-bottom:18px;">
                <div class="dash-widget">
                    <div class="dash-widget__head"><h3><i class="fas fa-calendar-day" aria-hidden="true"></i> Quick period</h3></div>
                    <div class="chart-placeholder" aria-hidden="true">
                        <span class="label">Today <?php echo htmlspecialchars($report_today); ?> · Month <?php echo htmlspecialchars($report_month.'/'.$report_year); ?></span>
                        <span class="bar" style="height:40%"></span><span class="bar" style="height:55%"></span><span class="bar" style="height:70%"></span><span class="bar" style="height:48%"></span><span class="bar" style="height:82%"></span><span class="bar" style="height:60%"></span>
                    </div>
                </div>
                <div class="dash-widget">
                    <div class="dash-widget__head"><h3><i class="fas fa-building" aria-hidden="true"></i> Department coverage</h3></div>
                    <div class="system-status-grid">
                        <div class="sys-chip"><div class="k">Departments</div><div class="v"><?php echo number_format(count($reports_departments)); ?></div></div>
                        <div class="sys-chip"><div class="k">Export</div><div class="v">Print / PDF</div></div>
                        <div class="sys-chip"><div class="k">Modules</div><div class="v">Dental · Health</div></div>
                        <div class="sys-chip"><div class="k">Filters</div><div class="v">Date · Dept</div></div>
                    </div>
                </div>
            </div>
            <div class="reports-modules" role="list">
                <?php foreach (['dental' => ['icon' => 'fa-tooth', 'title' => 'Dental clinic', 'desc' => 'Visits and activity for dental patient records.'], 'health' => ['icon' => 'fa-heartbeat', 'title' => 'Health / medical', 'desc' => 'Visits and activity for general medical patient records.']] as $mod => $meta): ?>
                <article class="report-module report-module--<?php echo htmlspecialchars($mod, ENT_QUOTES, 'UTF-8'); ?>" role="listitem">
                    <header class="report-module__head">
                        <div class="report-module__icon" aria-hidden="true">
                            <i class="fas <?php echo htmlspecialchars($meta['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                        </div>
                        <div class="report-module__titles">
                            <h3><?php echo htmlspecialchars($meta['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p><?php echo htmlspecialchars($meta['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </header>
                    <div class="report-module__body">
                        <div>
                            <div class="report-section-label">By period</div>
                            <div class="report-actions">
                                <a href="print_daily_report.php?module=<?php echo rawurlencode($mod); ?>&amp;case_date=<?php echo rawurlencode($report_today); ?>" target="_blank" rel="noopener noreferrer" class="report-action" aria-label="<?php echo htmlspecialchars(ucfirst($mod), ENT_QUOTES, 'UTF-8'); ?>: open daily report in new tab">
                                    <span class="report-action__icon" aria-hidden="true"><i class="fas fa-calendar-day"></i></span>
                                    <span class="report-action__text">
                                        <span class="report-action__title">Daily report</span>
                                        <span class="report-action__hint">Snapshot for the current day</span>
                                    </span>
                                    <span class="report-action__ext" aria-hidden="true" title="Opens in new tab"><i class="fas fa-external-link-alt"></i></span>
                                </a>
                                <a href="print_monthly_report.php?module=<?php echo rawurlencode($mod); ?>&amp;month=<?php echo rawurlencode($report_month); ?>&amp;year=<?php echo rawurlencode($report_year); ?>" target="_blank" rel="noopener noreferrer" class="report-action" aria-label="<?php echo htmlspecialchars(ucfirst($mod), ENT_QUOTES, 'UTF-8'); ?>: open monthly report in new tab">
                                    <span class="report-action__icon" aria-hidden="true"><i class="fas fa-calendar-alt"></i></span>
                                    <span class="report-action__text">
                                        <span class="report-action__title">Monthly report</span>
                                        <span class="report-action__hint">Roll-up for the current month</span>
                                    </span>
                                    <span class="report-action__ext" aria-hidden="true" title="Opens in new tab"><i class="fas fa-external-link-alt"></i></span>
                                </a>
                            </div>
                        </div>
                        <div>
                            <div class="report-section-label">Filtered</div>
                            <div class="report-actions">
                                <div class="report-filter-block">
                                    <div class="report-filter-block__head">
                                        <span class="report-filter-block__icon" aria-hidden="true"><i class="fas fa-calendar-day"></i></span>
                                        <div>
                                            <div class="report-filter-block__title">By visit date</div>
                                            <p class="report-filter-block__desc">Single-day visit list for <?php echo htmlspecialchars($mod, ENT_QUOTES, 'UTF-8'); ?> records.</p>
                                        </div>
                                    </div>
                                    <form class="report-filter-form" action="print_by_date.php" method="get" target="_blank">
                                        <input type="hidden" name="module" value="<?php echo htmlspecialchars($mod, ENT_QUOTES, 'UTF-8'); ?>">
                                        <div class="report-filter-form__row">
                                            <label for="report-date-<?php echo htmlspecialchars($mod, ENT_QUOTES, 'UTF-8'); ?>">Visit date</label>
                                            <input type="date" id="report-date-<?php echo htmlspecialchars($mod, ENT_QUOTES, 'UTF-8'); ?>" name="visit_date" class="form-control" value="<?php echo htmlspecialchars($report_today, ENT_QUOTES, 'UTF-8'); ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary report-filter-form__btn">
                                            <i class="fas fa-external-link-alt" aria-hidden="true"></i> Open report
                                        </button>
                                    </form>
                                </div>
                                <div class="report-filter-block">
                                    <div class="report-filter-block__head">
                                        <span class="report-filter-block__icon" aria-hidden="true"><i class="fas fa-building"></i></span>
                                        <div>
                                            <div class="report-filter-block__title">By department</div>
                                            <p class="report-filter-block__desc">All visits recorded under the selected department.</p>
                                        </div>
                                    </div>
                                    <?php if (! empty($reports_departments)): ?>
                                    <form class="report-filter-form" action="print_by_department.php" method="get" target="_blank">
                                        <input type="hidden" name="module" value="<?php echo htmlspecialchars($mod, ENT_QUOTES, 'UTF-8'); ?>">
                                        <div class="report-filter-form__row">
                                            <label for="report-dept-<?php echo htmlspecialchars($mod, ENT_QUOTES, 'UTF-8'); ?>">Department</label>
                                            <select id="report-dept-<?php echo htmlspecialchars($mod, ENT_QUOTES, 'UTF-8'); ?>" name="department" class="form-control" required>
                                                <?php foreach ($reports_departments as $dept): ?>
                                                <option value="<?php echo (int) $dept['id']; ?>"><?php echo htmlspecialchars($dept['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary report-filter-form__btn">
                                            <i class="fas fa-external-link-alt" aria-hidden="true"></i> Open report
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <p class="report-filter-empty">No departments are set up yet. Add departments in the database before using this report.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
