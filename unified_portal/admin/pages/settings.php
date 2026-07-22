<?php
/**
 * Modular page: settings
 * Included by wpu_page_router.php — do not access directly.
 */
                            $current_staff = is_array($wpu_current_staff)
                                ? $wpu_current_staff
                                : wpu_get_staff_signature($pdo);
                            ?>
                            <div class="content-card">
                                <div class="card-header">
                                    <h2 class="card-title">
                                        <i class="fas fa-cog" aria-hidden="true"></i>
                                        System settings
                                    </h2>
                                </div>
                                <div class="card-body">
                                    <div class="settings-page">
                                        <p class="settings-intro">
                                            <strong>Clinic branding and security</strong> for this admin station. Signing physician and document codes feed into printed certificates and referrals. Auto-lock and password apply to your own account session on this browser.
                                        </p>

                                        <section class="settings-panel" aria-labelledby="settings-physician-heading">
                                            <header class="settings-panel__head">
                                                <div class="settings-panel__icon" aria-hidden="true"><i class="fas fa-user-md"></i></div>
                                                <div class="settings-panel__titles">
                                                    <h3 id="settings-physician-heading">Signing physician</h3>
                                                    <p class="settings-panel__lead">Name, title, and license line shown on medical certificates and referrals.</p>
                                                </div>
                                            </header>
                                            <div class="settings-panel__body">
                                                <div class="settings-summary-grid" role="group" aria-label="Current physician details">
                                                    <div class="settings-summary-item">
                                                        <span class="settings-summary-item__label">Full name</span>
                                                        <span class="settings-summary-item__value"><?php echo htmlspecialchars($current_staff['name']); ?></span>
                                                    </div>
                                                    <div class="settings-summary-item">
                                                        <span class="settings-summary-item__label">Position</span>
                                                        <span class="settings-summary-item__value"><?php echo htmlspecialchars($current_staff['position']); ?></span>
                                                    </div>
                                                    <div class="settings-summary-item">
                                                        <span class="settings-summary-item__label">License</span>
                                                        <span class="settings-summary-item__value"><?php echo htmlspecialchars($current_staff['license_no']); ?></span>
                                                    </div>
                                                </div>
                                                <div class="settings-form-actions">
                                                    <button type="button" class="btn btn-primary" onclick="openStaffSignatureModal()">
                                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                                        Update physician
                                                    </button>
                                                </div>
                                            </div>
                                        </section>

                                        <section class="settings-panel" aria-labelledby="settings-codes-heading">
                                            <header class="settings-panel__head">
                                                <div class="settings-panel__icon" aria-hidden="true"><i class="fas fa-hashtag"></i></div>
                                                <div class="settings-panel__titles">
                                                    <h3 id="settings-codes-heading">Certificate &amp; referral codes</h3>
                                                    <p class="settings-panel__lead">Reference codes printed at the bottom of official documents.</p>
                                                </div>
                                            </header>
                                            <div class="settings-panel__body">
                                                <div class="settings-summary-grid" role="group" aria-label="Current document codes">
                                                    <div class="settings-summary-item settings-summary-item--mono">
                                                        <span class="settings-summary-item__label">Certificate code</span>
                                                        <span class="settings-summary-item__value"><?php echo htmlspecialchars($certificate_code !== '' ? $certificate_code : '—'); ?></span>
                                                    </div>
                                                    <div class="settings-summary-item settings-summary-item--mono">
                                                        <span class="settings-summary-item__label">Referral code</span>
                                                        <span class="settings-summary-item__value"><?php echo htmlspecialchars($referral_code !== '' ? $referral_code : '—'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="settings-form-actions">
                                                    <button type="button" class="btn btn-primary" onclick="openCertificateCodeModal()">
                                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                                        Update document codes
                                                    </button>
                                                </div>
                                            </div>
                                        </section>

                                        <section class="settings-panel" aria-labelledby="settings-autolock-heading">
                                            <header class="settings-panel__head">
                                                <div class="settings-panel__icon" aria-hidden="true"><i class="fas fa-lock"></i></div>
                                                <div class="settings-panel__titles">
                                                    <h3 id="settings-autolock-heading">Session auto-lock</h3>
                                                    <p class="settings-panel__lead">Require password again after idle time on this device.</p>
                                                </div>
                                                <div class="settings-panel__meta">
                                                    <span class="settings-status-pill <?php echo $enabled ? 'settings-status-pill--on' : 'settings-status-pill--off'; ?>">
                                                        <?php echo $enabled ? 'On' : 'Off'; ?>
                                                    </span>
                                                </div>
                                            </header>
                                            <div class="settings-panel__body">
                                                <form method="post" action="">
                                                    <input type="hidden" name="form_token" value="<?php echo htmlspecialchars(wpu_ensure_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                    <input type="hidden" name="action" value="save_autolock">
                                                    <label class="settings-checkbox-row" for="enabled">
                                                        <input type="checkbox" id="enabled" name="enabled" value="1" <?php echo $enabled ? 'checked' : ''; ?>>
                                                        <span class="settings-checkbox-row__text">
                                                            <strong>Enable auto-lock</strong>
                                                            <span>After no activity for the minutes below, the screen locks until the password is entered again.</span>
                                                        </span>
                                                    </label>
                                                    <div class="form-group">
                                                        <label for="timeout">Idle time before lock (minutes)</label>
                                                        <input type="number" class="form-control" id="timeout" name="timeout" value="<?php echo (int) ($timeout / 60000); ?>" min="1" step="1" required>
                                                        <span class="form-helper">Minimum 1 minute. Shorter times are safer on shared computers.</span>
                                                    </div>
                                                    <div class="settings-form-actions">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-save" aria-hidden="true"></i>
                                                            Save auto-lock
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </section>

                                        <section class="settings-panel" aria-labelledby="settings-password-heading">
                                            <header class="settings-panel__head">
                                                <div class="settings-panel__icon" aria-hidden="true"><i class="fas fa-key"></i></div>
                                                <div class="settings-panel__titles">
                                                    <h3 id="settings-password-heading">Account password</h3>
                                                    <p class="settings-panel__lead">Change the password for <strong><?php echo htmlspecialchars($current_user); ?></strong> on this system.</p>
                                                </div>
                                            </header>
                                            <div class="settings-panel__body">
                                                <form method="post" action="">
                                                    <input type="hidden" name="form_token" value="<?php echo htmlspecialchars(wpu_ensure_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                    <input type="hidden" name="action" value="change_password">
                                                    <div class="settings-form-grid-3">
                                                        <div class="form-group">
                                                            <label for="current_password">Current password</label>
                                                            <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="new_password">New password</label>
                                                            <input type="password" class="form-control" id="new_password" name="new_password" autocomplete="new-password" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="confirm_password">Confirm new password</label>
                                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" autocomplete="new-password" required>
                                                        </div>
                                                    </div>
                                                    <span class="form-helper">Use a strong password you do not reuse elsewhere. You will stay signed in after a successful change.</span>
                                                    <div class="settings-form-actions">
                                                        <button type="submit" class="btn btn-warning">Change password</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
