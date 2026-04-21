<style>
    .work-page .page-hero {
        background: linear-gradient(135deg, #123524 0%, #1f6f50 62%, #d7efe4 100%);
        border-radius: 24px;
        padding: 26px 28px;
        color: #fff;
        box-shadow: 0 18px 36px rgba(18, 53, 36, 0.16);
        margin-bottom: 22px;
    }

    .work-page .page-kicker {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        opacity: 0.8;
        margin-bottom: 8px;
    }

    .work-page .page-title {
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .work-page .page-copy {
        margin-bottom: 0;
        color: rgba(255, 255, 255, 0.82);
        max-width: 720px;
    }

    .work-page .page-card {
        background: #fff;
        border: 1px solid #dcebe1;
        border-radius: 24px;
        box-shadow: 0 14px 30px rgba(18, 53, 36, 0.07);
        overflow: hidden;
    }

    .work-page .page-card .card-body {
        padding: 24px;
    }

    .work-page .page-card + .page-card {
        margin-top: 20px;
    }

    .work-page .page-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 20px;
    }

    .work-page .page-card-header h4,
    .work-page .page-card-header h5 {
        margin-bottom: 6px;
        font-weight: 700;
        color: #203126;
    }

    .work-page .page-card-header p {
        margin-bottom: 0;
        color: #6f7f74;
        font-size: 14px;
    }

    .work-page .wizard-steps {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 18px;
    }

    .work-page .wizard-step {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border-radius: 999px;
        background: #edf7f1;
        color: #4b6554;
        font-size: 13px;
        font-weight: 600;
    }

    .work-page .wizard-step.is-active {
        background: linear-gradient(135deg, var(--theme-green-700), var(--theme-green-500));
        color: #fff;
        box-shadow: 0 10px 20px rgba(31, 111, 80, 0.16);
    }

    .work-page .section-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .work-page .field-card {
        border: 1px solid #e1eee6;
        border-radius: 18px;
        padding: 16px;
        background: #fcfefd;
        height: 100%;
    }

    .work-page .field-card.is-full {
        grid-column: 1 / -1;
    }

    .work-page .field-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #284234;
        margin-bottom: 8px;
    }

    .work-page .field-hint {
        display: block;
        margin-top: 6px;
        color: #71827a;
        font-size: 12px;
    }

    .work-page .field-preview {
        background: #f3faf5;
    }

    .work-page .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 22px;
    }

    .work-page .form-actions.is-end {
        justify-content: flex-end;
    }

    .work-page .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .work-page .detail-item {
        border: 1px solid #e1eee6;
        border-radius: 18px;
        padding: 16px;
        background: #fcfefd;
    }

    .work-page .detail-item.is-full {
        grid-column: 1 / -1;
    }

    .work-page .detail-item span {
        display: block;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #73847b;
        margin-bottom: 6px;
    }

    .work-page .detail-item strong,
    .work-page .detail-item .detail-value {
        color: #203126;
        font-size: 15px;
        font-weight: 700;
    }

    .work-page .detail-item p {
        margin-bottom: 0;
        color: #4e6257;
    }

    .work-page .context-banner {
        display: grid;
        grid-template-columns: 48px 1fr;
        gap: 14px;
        align-items: center;
        padding: 16px 18px;
        border-radius: 20px;
        background: linear-gradient(135deg, #eff8f2, #e2f2e8);
        border: 1px solid #cfe7d8;
        margin-bottom: 18px;
    }

    .work-page .context-banner.is-warning {
        background: linear-gradient(135deg, #fff8e4, #fff2cf);
        border-color: #f1deb0;
    }

    .work-page .context-banner img {
        width: 48px;
        height: 48px;
        object-fit: contain;
    }

    .work-page .context-banner strong {
        display: block;
        color: #203126;
        margin-bottom: 4px;
    }

    .work-page .context-banner p {
        margin-bottom: 0;
        color: #596b62;
        font-size: 13px;
    }

    .work-page .detail-actions {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .work-page .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .work-page .summary-tile {
        border: 1px solid #dcebe1;
        border-radius: 18px;
        padding: 18px;
        background: linear-gradient(180deg, #fcfefd, #f4fbf6);
    }

    .work-page .summary-tile span {
        display: block;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #73847b;
        margin-bottom: 8px;
    }

    .work-page .summary-tile strong {
        display: block;
        color: #203126;
        font-size: 20px;
        font-weight: 700;
    }

    .work-page .summary-tile strong small {
        font-size: 13px;
        color: #73847b;
        font-weight: 600;
    }

    .work-page .detail-tabs {
        gap: 10px;
        margin-bottom: 20px;
        border-bottom: 0;
    }

    .work-page .detail-tabs .nav-link {
        border: 1px solid #dcebe1;
        border-radius: 999px;
        background: #f3faf5;
        color: #4b6554;
        font-weight: 700;
        padding: 10px 18px;
        transition: all 0.2s ease;
    }

    .work-page .detail-tabs .nav-link.active,
    .work-page .detail-tabs .nav-link:hover {
        background: linear-gradient(135deg, var(--theme-green-700), var(--theme-green-500));
        border-color: transparent;
        color: #fff;
        box-shadow: 0 10px 20px rgba(31, 111, 80, 0.16);
    }

    .work-page .detail-tab-content {
        margin-top: 8px;
    }

    .work-page .history-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 16px;
    }

    .work-page .history-header h5 {
        margin-bottom: 6px;
        font-weight: 700;
        color: #203126;
    }

    .work-page .history-header p {
        margin-bottom: 0;
        color: #6f7f74;
        font-size: 14px;
    }

    .work-page .history-table {
        margin-bottom: 0;
    }

    .work-page .table-message {
        min-width: 260px;
        color: #4e6257;
        white-space: normal;
        line-height: 1.5;
    }

    @media (max-width: 991.98px) {
        .work-page .summary-grid,
        .work-page .section-grid,
        .work-page .detail-grid {
            grid-template-columns: 1fr;
        }

        .work-page .page-card-header,
        .work-page .form-actions,
        .work-page .detail-actions,
        .work-page .history-header {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
