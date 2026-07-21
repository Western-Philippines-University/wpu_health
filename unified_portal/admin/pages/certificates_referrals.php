<?php
/**
 * Certificates & referrals list page (tabbed).
 * Variables from admin.php + wpu_page_data.php.
 */
if ($page === 'certificates') {
    $active_tab = 'certificates';
} elseif ($page === 'referrals') {
    $active_tab = 'referrals';
}

$cert_offset = ($cert_page - 1) * $cert_ref_items_per_page;
$ref_offset = ($ref_page - 1) * $cert_ref_items_per_page;
?>
<div class="content-card cr-combined">
    <div class="cr-tabs-wrap">
        <div class="cr-tabs" role="tablist" aria-label="Certificates and referrals">
            <button type="button" role="tab" id="cr-tab-cert-btn"
                    aria-selected="<?php echo $active_tab === 'certificates' ? 'true' : 'false'; ?>"
                    aria-controls="certificates-tab"
                    class="cr-tab <?php echo $active_tab === 'certificates' ? 'is-active' : ''; ?>"
                    onclick="switchTab('certificates')">
                <i class="fas fa-file-medical" aria-hidden="true"></i> Medical certificates
            </button>
            <button type="button" role="tab" id="cr-tab-ref-btn"
                    aria-selected="<?php echo $active_tab === 'referrals' ? 'true' : 'false'; ?>"
                    aria-controls="referrals-tab"
                    class="cr-tab <?php echo $active_tab === 'referrals' ? 'is-active' : ''; ?>"
                    onclick="switchTab('referrals')">
                <i class="fas fa-ambulance" aria-hidden="true"></i> Referrals
            </button>
        </div>
    </div>

    <div id="certificates-tab" class="cr-tab-panel" role="tabpanel" aria-labelledby="cr-tab-cert-btn"
         <?php echo $active_tab !== 'certificates' ? 'hidden' : ''; ?>>
        <div class="cr-intro">
            <p>Browse, search, and manage issued medical certificates. Use the actions column to view, edit, print, or remove entries.</p>
            <span class="cr-count-pill"><?php echo number_format((int) $cert_total); ?> total</span>
        </div>
        <div class="cr-toolbar">
            <button type="button" class="btn btn-primary" onclick="openCreateCertificateModal()">
                <i class="fas fa-plus" aria-hidden="true"></i> Create certificate
            </button>
            <div class="cr-search-row">
                <label class="visually-hidden" for="cert-search-input">Search certificates</label>
                <input type="search" id="cert-search-input" class="form-control"
                       placeholder="Search by patient name…"
                       value="<?php echo htmlspecialchars($cert_search, ENT_QUOTES, 'UTF-8'); ?>"
                       onkeydown="if (event.key === 'Enter') { event.preventDefault(); submitCertSearch(); }">
                <button type="button" class="btn btn-secondary" onclick="submitCertSearch()">Search</button>
                <span class="cr-search-hint">Press Enter or click Search</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Age</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Exam date</th>
                        <th scope="col">MC No.</th>
                        <th scope="col">Receipt</th>
                        <th scope="col">Created</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($certificates_result instanceof mysqli_result && $certificates_result->num_rows > 0): ?>
                        <?php $cert_counter = $cert_offset + 1; ?>
                        <?php while ($row = $certificates_result->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?php echo (int) $cert_counter; ?></strong></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars((string) $row['age']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['examination_date'])); ?></td>
                                <td><span class="badge badge-success"><?php echo htmlspecialchars($row['mc_no'] ?? ''); ?></span></td>
                                <td>
                                    <?php if (! empty($row['receipt_no'])): ?>
                                        <span class="badge badge-info"><?php echo htmlspecialchars($row['receipt_no']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                <td class="actions-cell">
                                    <button type="button" class="btn btn-view btn-icon btn-sm" title="View" onclick="viewCertificate(<?php echo (int) $row['id']; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-edit btn-icon btn-sm" title="Edit" onclick="editCertificate(<?php echo (int) $row['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-delete btn-icon btn-sm" title="Delete"
                                            onclick="deleteCertificate(<?php echo (int) $row['id']; ?>, <?php echo json_encode((string) $row['name'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button type="button" class="btn btn-print btn-icon btn-sm" title="Print"
                                            onclick="window.open('print_certificate.php?id=<?php echo (int) $row['id']; ?>', '_blank')">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php $cert_counter++; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">
                                <div class="table-empty" role="status">
                                    <span class="table-empty__icon" aria-hidden="true"><i class="fas fa-file-medical"></i></span>
                                    <p class="table-empty__title">No certificates found</p>
                                    <p class="table-empty__hint"><?php echo $cert_search !== '' ? 'No matches for your search. Try another name or clear the filter.' : 'Create a certificate to see it listed here.'; ?></p>
                                    <?php if ($cert_search === ''): ?>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="openCreateCertificateModal()">Create certificate</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if ($cert_total > 0): ?>
        <div class="pagination">
            <div class="pagination-info">
                Showing <?php echo (int) ($cert_offset + 1); ?> to <?php echo (int) min($cert_offset + $cert_ref_items_per_page, $cert_total); ?> of <?php echo (int) $cert_total; ?> entries
            </div>
            <div class="pagination-controls">
                <button type="button" <?php echo $cert_page <= 1 ? 'disabled' : ''; ?> onclick="changePageTab('certificates', <?php echo (int) ($cert_page - 1); ?>)">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i> Previous
                </button>
                <span class="pagination-page-label">Page <?php echo (int) $cert_page; ?> of <?php echo max(1, (int) $cert_total_pages); ?></span>
                <button type="button" <?php echo ($cert_total_pages < 2 || $cert_page >= $cert_total_pages) ? 'disabled' : ''; ?> onclick="changePageTab('certificates', <?php echo (int) ($cert_page + 1); ?>)">
                    Next <i class="fas fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div id="referrals-tab" class="cr-tab-panel" role="tabpanel" aria-labelledby="cr-tab-ref-btn"
         <?php echo $active_tab !== 'referrals' ? 'hidden' : ''; ?>>
        <div class="cr-intro cr-intro--referrals">
            <p>Two-way referral forms sent to external hospitals or clinics. Search by patient name and manage records below.</p>
            <span class="cr-count-pill"><?php echo number_format((int) $ref_total); ?> total</span>
        </div>
        <div class="cr-toolbar">
            <button type="button" class="btn btn-primary" onclick="openCreateReferralModal()">
                <i class="fas fa-plus" aria-hidden="true"></i> Create referral
            </button>
            <div class="cr-search-row">
                <label class="visually-hidden" for="ref-search-input">Search referrals</label>
                <input type="search" id="ref-search-input" class="form-control"
                       placeholder="Search by patient name…"
                       value="<?php echo htmlspecialchars($ref_search, ENT_QUOTES, 'UTF-8'); ?>"
                       onkeydown="if (event.key === 'Enter') { event.preventDefault(); submitRefSearch(); }">
                <button type="button" class="btn btn-secondary" onclick="submitRefSearch()">Search</button>
                <span class="cr-search-hint">Press Enter or click Search</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Patient</th>
                        <th scope="col">Age</th>
                        <th scope="col">Sex</th>
                        <th scope="col">Hospital / clinic</th>
                        <th scope="col">Referral date</th>
                        <th scope="col">Created</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($referrals_result instanceof mysqli_result && $referrals_result->num_rows > 0): ?>
                        <?php $ref_counter = $ref_offset + 1; ?>
                        <?php while ($row = $referrals_result->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?php echo (int) $ref_counter; ?></strong></td>
                                <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                <td><?php echo htmlspecialchars((string) $row['patient_age']); ?></td>
                                <td><?php echo htmlspecialchars($row['patient_sex']); ?></td>
                                <td><?php echo htmlspecialchars($row['hospital_clinic']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['referral_date'])); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                <td class="actions-cell">
                                    <button type="button" class="btn btn-view btn-icon btn-sm" title="View" onclick="viewReferral(<?php echo (int) $row['id']; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-edit btn-icon btn-sm" title="Edit" onclick="editReferral(<?php echo (int) $row['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-delete btn-icon btn-sm" title="Delete"
                                            onclick="deleteReferral(<?php echo (int) $row['id']; ?>, <?php echo json_encode((string) $row['patient_name'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button type="button" class="btn btn-print btn-icon btn-sm" title="Print"
                                            onclick="window.open('print_referral.php?id=<?php echo (int) $row['id']; ?>', '_blank')">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php $ref_counter++; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">
                                <div class="table-empty" role="status">
                                    <span class="table-empty__icon" aria-hidden="true"><i class="fas fa-ambulance"></i></span>
                                    <p class="table-empty__title">No referrals found</p>
                                    <p class="table-empty__hint"><?php echo $ref_search !== '' ? 'No matches for your search.' : 'Create a referral to see it listed here.'; ?></p>
                                    <?php if ($ref_search === ''): ?>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="openCreateReferralModal()">Create referral</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if ($ref_total > 0): ?>
        <div class="pagination">
            <div class="pagination-info">
                Showing <?php echo (int) ($ref_offset + 1); ?> to <?php echo (int) min($ref_offset + $cert_ref_items_per_page, $ref_total); ?> of <?php echo (int) $ref_total; ?> entries
            </div>
            <div class="pagination-controls">
                <button type="button" <?php echo $ref_page <= 1 ? 'disabled' : ''; ?> onclick="changePageTab('referrals', <?php echo (int) ($ref_page - 1); ?>)">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i> Previous
                </button>
                <span class="pagination-page-label">Page <?php echo (int) $ref_page; ?> of <?php echo max(1, (int) $ref_total_pages); ?></span>
                <button type="button" <?php echo ($ref_total_pages < 2 || $ref_page >= $ref_total_pages) ? 'disabled' : ''; ?> onclick="changePageTab('referrals', <?php echo (int) ($ref_page + 1); ?>)">
                    Next <i class="fas fa-chevron-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
