<style>
                /* Semua style di file ini dibungkus dengan .list-page agar aman dan tidak bocor ke halaman lain. */
                .list-page .page-hero {
                    background: linear-gradient(135deg, #123524 0%, #1f6f50 62%, #d7efe4 100%);
                    border-radius: 24px;
                    padding: 26px 28px;
                    color: #fff;
                    box-shadow: 0 18px 36px rgba(18, 53, 36, 0.16);
                    margin-bottom: 22px;
                }

                .list-page .page-kicker {
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.16em;
                    opacity: 0.8;
                    margin-bottom: 8px;
                }

                .list-page .page-title {
                    font-size: 30px;
                    font-weight: 700;
                    margin-bottom: 8px;
                }

                .list-page .page-copy {
                    margin-bottom: 0;
                    color: rgba(255, 255, 255, 0.82);
                    max-width: 680px;
                }

                .list-page .hero-stat-grid {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 12px;
                    max-width: 420px;
                    margin-left: auto;
                }

                .list-page .hero-stat {
                    background: rgba(255, 255, 255, 0.12);
                    border: 1px solid rgba(255, 255, 255, 0.12);
                    border-radius: 18px;
                    padding: 14px 16px;
                }

                .list-page .hero-stat span {
                    display: block;
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.08em;
                    color: rgba(255, 255, 255, 0.72);
                    margin-bottom: 4px;
                }

                .list-page .hero-stat strong {
                    font-size: 24px;
                    font-weight: 700;
                }

                .list-page .surface-card {
                    background: #fff;
                    border: 1px solid #dcebe1;
                    border-radius: 22px;
                    box-shadow: 0 14px 30px rgba(18, 53, 36, 0.07);
                    margin-bottom: 20px;
                }

                .list-page .surface-card .card-body {
                    padding: 22px;
                }

                .list-page .section-heading {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    gap: 16px;
                    margin-bottom: 18px;
                }

                .list-page .section-heading h4,
                .list-page .section-heading h5 {
                    margin-bottom: 6px;
                    font-weight: 700;
                    color: #203126;
                }

                .list-page .section-caption {
                    margin-bottom: 0;
                    color: #6f7f74;
                    font-size: 14px;
                }

                .list-page .toolbar-grid {
                    display: grid;
                    grid-template-columns: minmax(240px, 1.2fr) minmax(280px, 1fr);
                    gap: 16px;
                    align-items: end;
                }

                .list-page .search-box {
                    position: relative;
                }

                .list-page .search-box i {
                    position: absolute;
                    left: 14px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #6f7f74;
                }

                .list-page .search-box .form-control {
                    padding-left: 42px;
                    height: 44px;
                    border-radius: 14px;
                }

                .list-page .stack-actions {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                    justify-content: flex-end;
                    align-items: center;
                }

                .list-page .file-input-inline {
                    min-width: 180px;
                    max-width: 220px;
                }

                .list-page .summary-strip {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                    margin-bottom: 16px;
                }

                .list-page .summary-chip {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 8px 12px;
                    border-radius: 999px;
                    background: #eff8f2;
                    color: #1f6f50;
                    font-size: 13px;
                    font-weight: 600;
                }

                .list-page .summary-chip i {
                    font-size: 16px;
                }

                .list-page .table-shell {
                    border: 1px solid #e1eee6;
                    border-radius: 18px;
                    overflow: hidden;
                }

                .list-page .table {
                    margin-bottom: 0;
                }

                .list-page .table thead th {
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.06em;
                    border-bottom: 1px solid #e1eee6;
                }

                .list-page .table td {
                    vertical-align: middle;
                    border-color: #edf5f0;
                    padding-top: 16px;
                    padding-bottom: 16px;
                }

                .list-page .name-cell strong {
                    display: block;
                    color: #203126;
                    font-size: 15px;
                }

                .list-page .name-cell small,
                .list-page .muted-meta {
                    color: #76877c;
                    font-size: 13px;
                }

                .list-page .amount-pill {
                    display: inline-flex;
                    align-items: center;
                    padding: 7px 12px;
                    border-radius: 999px;
                    background: #f3faf5;
                    color: #1f6f50;
                    font-weight: 700;
                }

                .list-page .quality-badge {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 110px;
                    padding: 8px 12px;
                    border-radius: 999px;
                    font-size: 12px;
                    font-weight: 700;
                }

                .list-page .quality-badge.success {
                    background: #dff3e6;
                    color: #1f6f50;
                }

                .list-page .quality-badge.warning {
                    background: #fff3d6;
                    color: #9a6a00;
                }

                .list-page .quality-badge.info {
                    background: #dff2f7;
                    color: #176f86;
                }

                .list-page .quality-badge.danger {
                    background: #fbe0e3;
                    color: #b63a4b;
                }

                .list-page .quality-badge.secondary {
                    background: #eceff1;
                    color: #66737d;
                }

                .list-page .action-group {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                    justify-content: center;
                }

                .list-page .action-group .btn {
                    border-radius: 10px;
                }

                .list-page .empty-state {
                    padding: 40px 24px;
                    text-align: center;
                    background: #fbfefd;
                    color: #708077;
                }

                .list-page .empty-state i {
                    font-size: 36px;
                    color: #8bcfb0;
                    margin-bottom: 12px;
                    display: block;
                }

                .list-page .footer-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 12px;
                    margin-top: 18px;
                    flex-wrap: wrap;
                }

                .list-page .option-card {
                    border: 1px solid #dcebe1;
                    border-radius: 14px;
                    transition: border-color 0.2s ease, background-color 0.2s ease;
                    background: #fff;
                }

                .list-page .option-card:hover {
                    border-color: #8bcfb0;
                    background: #f8fcf9;
                }

                @media (max-width: 991.98px) {
                    .list-page .hero-stat-grid,
                    .list-page .toolbar-grid {
                        grid-template-columns: 1fr;
                    }

                    .list-page .stack-actions {
                        justify-content: flex-start;
                    }
                }
            </style>
