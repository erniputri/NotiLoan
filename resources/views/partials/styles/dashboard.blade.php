<style>
                /* Namespace .dashboard-page dipakai agar styling dashboard tidak bocor ke halaman modul lain. */
                .dashboard-page .hero-panel {
                    background: linear-gradient(135deg, #123524 0%, #1f6f50 58%, #d7efe4 100%);
                    border-radius: 24px;
                    padding: 28px;
                    color: #fff;
                    box-shadow: 0 18px 38px rgba(18, 53, 36, 0.18);
                }

                .dashboard-page .hero-kicker {
                    font-size: 12px;
                    letter-spacing: 0.18em;
                    text-transform: uppercase;
                    opacity: 0.82;
                    margin-bottom: 10px;
                }

                .dashboard-page .hero-title {
                    font-size: 30px;
                    font-weight: 700;
                    margin-bottom: 8px;
                }

                .dashboard-page .hero-copy {
                    max-width: 620px;
                    color: rgba(255, 255, 255, 0.82);
                    margin-bottom: 0;
                }

                .dashboard-page .hero-stat-grid {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 14px;
                }

                .dashboard-page .hero-stat {
                    background: rgba(255, 255, 255, 0.12);
                    border: 1px solid rgba(255, 255, 255, 0.12);
                    border-radius: 18px;
                    padding: 16px 18px;
                    backdrop-filter: blur(6px);
                }

                .dashboard-page .hero-stat-label {
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.08em;
                    color: rgba(255, 255, 255, 0.72);
                }

                .dashboard-page .hero-stat-value {
                    font-size: 28px;
                    font-weight: 700;
                    margin-top: 4px;
                    margin-bottom: 4px;
                }

                .dashboard-page .hero-stat-meta {
                    font-size: 13px;
                    color: rgba(255, 255, 255, 0.76);
                    margin-bottom: 0;
                }

                .dashboard-page .metric-card {
                    border: 0;
                    border-radius: 20px;
                    height: 100%;
                    box-shadow: 0 12px 30px rgba(20, 30, 58, 0.08);
                }

                .dashboard-page .metric-card .card-body {
                    padding: 20px;
                }

                .dashboard-page .metric-topline {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 18px;
                }

                .dashboard-page .metric-label {
                    font-size: 13px;
                    font-weight: 600;
                    color: #6c757d;
                    text-transform: uppercase;
                    letter-spacing: 0.06em;
                }

                .dashboard-page .metric-icon {
                    width: 42px;
                    height: 42px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 12px;
                    font-size: 20px;
                }

                .dashboard-page .metric-value {
                    font-size: 34px;
                    font-weight: 700;
                    color: #1d2433;
                    line-height: 1;
                    margin-bottom: 10px;
                }

                .dashboard-page .metric-meta {
                    margin-bottom: 0;
                    color: #6c757d;
                    font-size: 14px;
                }

                .dashboard-page .metric-meta strong {
                    color: #1d2433;
                }

                .dashboard-page .surface-card {
                    border: 0;
                    border-radius: 22px;
                    box-shadow: 0 12px 30px rgba(20, 30, 58, 0.08);
                    height: 100%;
                }

                .dashboard-page .surface-card .card-body {
                    padding: 22px;
                }

                .dashboard-page .section-heading {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 12px;
                    margin-bottom: 18px;
                }

                .dashboard-page .section-heading h4,
                .dashboard-page .section-heading h5 {
                    margin-bottom: 0;
                    font-weight: 700;
                    color: #202633;
                }

                .dashboard-page .section-caption {
                    color: #6c757d;
                    font-size: 14px;
                    margin-bottom: 0;
                }

                .dashboard-page .mini-stat-list {
                    display: grid;
                    gap: 12px;
                }

                .dashboard-page .mini-stat-item {
                    border: 1px solid #edf1f7;
                    border-radius: 16px;
                    padding: 14px 16px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .dashboard-page .mini-stat-item span {
                    color: #6c757d;
                    font-size: 14px;
                }

                .dashboard-page .mini-stat-item strong {
                    font-size: 20px;
                    color: #202633;
                }

                .dashboard-page .priority-list {
                    display: grid;
                    gap: 10px;
                }

                .dashboard-page .section-chip {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 6px 10px;
                    border-radius: 999px;
                    background: #f3f6fb;
                    color: #566074;
                    font-size: 12px;
                    font-weight: 600;
                    white-space: nowrap;
                }

                .dashboard-page .priority-item {
                    border: 1px solid #edf1f7;
                    border-radius: 16px;
                    padding: 14px 16px;
                }

                .dashboard-page .priority-head {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 14px;
                }

                .dashboard-page .priority-name {
                    margin-bottom: 2px;
                    font-size: 15px;
                    font-weight: 700;
                    color: #202633;
                }

                .dashboard-page .priority-subtitle {
                    margin-bottom: 0;
                    color: #6c757d;
                    font-size: 13px;
                }

                .dashboard-page .priority-meta {
                    display: grid;
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    gap: 10px;
                    margin-top: 12px;
                }

                .dashboard-page .priority-meta-card {
                    background: #f7f9fc;
                    border-radius: 14px;
                    padding: 10px 12px;
                }

                .dashboard-page .priority-meta-card span {
                    display: block;
                    font-size: 12px;
                    color: #6c757d;
                    margin-bottom: 2px;
                }

                .dashboard-page .priority-meta-card strong {
                    color: #202633;
                    font-size: 14px;
                }

                .dashboard-page .activity-table th {
                    border-top: 0;
                    font-size: 12px;
                    letter-spacing: 0.05em;
                    text-transform: uppercase;
                    color: #8a90a2;
                }

                .dashboard-page .activity-table td {
                    vertical-align: middle;
                    color: #2d3548;
                }

                .dashboard-page .action-col {
                    width: 120px;
                    text-align: right;
                }

                .dashboard-page .table-shell {
                    border: 1px solid #edf1f7;
                    border-radius: 18px;
                    overflow: hidden;
                }

                .dashboard-page .section-note {
                    margin-top: 14px;
                    margin-bottom: 0;
                    color: #7c8599;
                    font-size: 13px;
                }

                .dashboard-page .section-pagination {
                    margin-top: 14px;
                    display: flex;
                    justify-content: flex-end;
                }

                .dashboard-page .section-pagination .pagination {
                    margin-bottom: 0;
                }

                .dashboard-page .empty-state {
                    border: 1px dashed #d9e2ef;
                    border-radius: 18px;
                    padding: 28px 18px;
                    text-align: center;
                    color: #7c8599;
                    background: #fafcff;
                }

                .dashboard-page .chart-shell {
                    height: 280px;
                }

                .dashboard-page .detail-grid {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 12px;
                }

                .dashboard-page .detail-card {
                    background: #f7fbf8;
                    border: 1px solid #e1eee6;
                    border-radius: 16px;
                    padding: 14px 16px;
                }

                .dashboard-page .detail-card span {
                    display: block;
                    font-size: 12px;
                    color: #6c757d;
                    margin-bottom: 4px;
                    text-transform: uppercase;
                    letter-spacing: 0.04em;
                }

                .dashboard-page .detail-card strong {
                    color: #202633;
                    font-size: 15px;
                }

                .dashboard-page .detail-highlight {
                    background: linear-gradient(135deg, #eef8f2, #dff1e7);
                    border: 1px solid #cae6d5;
                    border-radius: 18px;
                    padding: 16px 18px;
                    margin-bottom: 16px;
                }

                .dashboard-page .detail-highlight h6 {
                    margin-bottom: 6px;
                    font-size: 18px;
                    font-weight: 700;
                    color: #184b33;
                }

                .dashboard-page .detail-highlight p {
                    margin-bottom: 0;
                    color: #476756;
                    font-size: 14px;
                }

                .dashboard-page .modal-content {
                    border: 0;
                    border-radius: 24px;
                    overflow: hidden;
                    box-shadow: 0 24px 60px rgba(18, 53, 36, 0.18);
                }

                .dashboard-page .modal-header {
                    background: linear-gradient(135deg, #123524 0%, #1f6f50 100%);
                    color: #fff;
                    border-bottom: 0;
                    padding: 18px 22px;
                }

                .dashboard-page .modal-header .close {
                    color: #fff;
                    opacity: 0.85;
                    text-shadow: none;
                }

                .dashboard-page .modal-body {
                    padding: 22px;
                }

                @media (max-width: 991.98px) {
                    .dashboard-page .hero-panel {
                        padding: 22px;
                    }

                    .dashboard-page .hero-stat-grid,
                    .dashboard-page .priority-meta,
                    .dashboard-page .detail-grid {
                        grid-template-columns: 1fr;
                    }
                }
            </style>
