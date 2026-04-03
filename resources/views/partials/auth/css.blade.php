<!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --auth-primary: #123524;
            --auth-secondary: #1f6f50;
            --auth-accent: #d29a2b;
            --auth-highlight: #edf7f0;
            --auth-text: #153427;
            --auth-muted: #6c7f75;
            --auth-border: rgba(18, 53, 36, 0.12);
            --auth-shadow: 0 24px 60px rgba(12, 39, 27, 0.22);
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: "Manrope", "Segoe UI", sans-serif;
            color: var(--auth-text);
            background:
                radial-gradient(circle at top left, rgba(215, 239, 228, 0.95), transparent 38%),
                radial-gradient(circle at right bottom, rgba(210, 154, 43, 0.22), transparent 24%),
                linear-gradient(135deg, #0f261c 0%, #174834 44%, #24553f 100%);
            position: relative;
            overflow-x: hidden;
        }

        body::before,
        body::after {
            content: "";
            position: fixed;
            border-radius: 999px;
            filter: blur(18px);
            opacity: 0.35;
            pointer-events: none;
        }

        body::before {
            width: 260px;
            height: 260px;
            top: -80px;
            right: -70px;
            background: rgba(210, 154, 43, 0.35);
        }

        body::after {
            width: 300px;
            height: 300px;
            left: -120px;
            bottom: -100px;
            background: rgba(215, 239, 228, 0.18);
        }

        .auth-shell {
            min-height: 100vh;
            padding: 32px 0;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 30px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.88);
            box-shadow: var(--auth-shadow);
            backdrop-filter: blur(16px);
        }

        .auth-form-panel {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, rgba(244, 248, 245, 0.98) 100%);
        }

        .auth-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(31, 111, 80, 0.1);
            color: var(--auth-secondary);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .auth-title {
            font-size: clamp(2rem, 3vw, 2.7rem);
            line-height: 1.1;
            font-weight: 800;
            color: var(--auth-primary);
            margin-bottom: 12px;
        }

        .auth-copy {
            color: var(--auth-muted);
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 0;
        }

        .auth-visual {
            position: relative;
            min-height: 100%;
            padding: 36px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background:
                linear-gradient(180deg, rgba(9, 34, 23, 0.16) 0%, rgba(9, 34, 23, 0.84) 82%),
                url('{{ asset('assets/images/auth/oil-palm-login.jpg') }}') center/cover no-repeat;
        }

        .auth-visual::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, rgba(210, 154, 43, 0.18), transparent 35%),
                linear-gradient(0deg, rgba(9, 34, 23, 0.58), rgba(9, 34, 23, 0.05));
        }

        .auth-visual > * {
            position: relative;
            z-index: 1;
        }

        .auth-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .auth-brand-mark {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.16);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            backdrop-filter: blur(8px);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.14);
        }

        .auth-brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .auth-brand-label {
            margin: 0;
            font-size: 12px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.74);
        }

        .auth-brand-name {
            margin: 2px 0 0;
            font-size: 24px;
            font-weight: 800;
        }

        .auth-visual-headline {
            max-width: 440px;
            margin-top: auto;
        }

        .auth-visual-headline h2 {
            font-size: clamp(2rem, 3vw, 3rem);
            font-weight: 800;
            line-height: 1.08;
            margin-bottom: 16px;
        }

        .auth-visual-headline p {
            max-width: 400px;
            color: rgba(255, 255, 255, 0.82);
            margin-bottom: 0;
            font-size: 15px;
            line-height: 1.8;
        }

        .auth-stat-grid {
            margin-top: 28px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .auth-stat {
            padding: 14px 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
        }

        .auth-stat-label {
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.76);
            margin-bottom: 6px;
        }

        .auth-stat-value {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 0;
        }

        .auth-input-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--auth-primary);
        }

        .auth-input-wrap {
            position: relative;
        }

        .auth-input-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: #6f8578;
            font-size: 16px;
            pointer-events: none;
        }

        .auth-input {
            height: 56px;
            border-radius: 16px;
            border: 1px solid var(--auth-border);
            background: #ffffff;
            padding: 0 18px 0 46px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .auth-input:focus {
            border-color: rgba(31, 111, 80, 0.42);
            box-shadow: 0 0 0 4px rgba(31, 111, 80, 0.12);
        }

        .auth-submit {
            height: 56px;
            border: 0;
            border-radius: 18px;
            font-weight: 800;
            letter-spacing: 0.02em;
            background: linear-gradient(135deg, var(--auth-primary), var(--auth-secondary));
            box-shadow: 0 16px 30px rgba(18, 53, 36, 0.22);
        }

        .auth-submit:hover,
        .auth-submit:focus {
            background: linear-gradient(135deg, #0f2c1f, #245d45);
        }

        .auth-link {
            color: var(--auth-secondary);
            text-decoration: none;
        }

        .auth-link:hover {
            color: var(--auth-primary);
        }

        .auth-note {
            color: var(--auth-muted);
            font-size: 14px;
        }

        .auth-alert {
            border: 0;
            border-radius: 16px;
            background: rgba(214, 69, 80, 0.1);
            color: #8c2130;
        }

        @media (max-width: 991.98px) {
            .auth-shell {
                padding: 20px 0;
            }

            .auth-card {
                border-radius: 24px;
            }

            .auth-form-panel {
                padding: 2rem !important;
            }

            .auth-visual {
                min-height: 420px;
                padding: 28px;
            }
        }

        @media (max-width: 767.98px) {
            .auth-form-panel {
                padding: 1.5rem !important;
            }

            .auth-stat-grid {
                grid-template-columns: 1fr;
            }

            .auth-visual-headline h2 {
                font-size: 2rem;
            }
        }
    </style>
