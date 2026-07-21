@extends('layouts.admin')

@section('title', __('Admin sign in'))

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ── Design tokens ── */
        :root {
            --al-primary: #2563EB;
            --al-secondary: #3B82F6;
            --al-accent: #60A5FA;
            --al-bg: #F8FAFC;
            --al-success: #10B981;
            --al-danger: #EF4444;
            --al-text: #111827;
            --al-muted: #6B7280;
            --al-surface: rgba(255, 255, 255, 0.78);
            --al-surface-solid: #ffffff;
            --al-border: rgba(148, 163, 184, 0.35);
            --al-input-bg: #ffffff;
            --al-panel-left: linear-gradient(155deg, #0f172a 0%, #1e3a8a 48%, #2563eb 100%);
            --al-glass-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.4) inset;
            --al-radius: 22px;
            --al-font: 'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --al-bg: #0B1220;
                --al-text: #F1F5F9;
                --al-muted: #94A3B8;
                --al-surface: rgba(15, 23, 42, 0.72);
                --al-surface-solid: #111827;
                --al-border: rgba(148, 163, 184, 0.22);
                --al-input-bg: rgba(30, 41, 59, 0.9);
                --al-panel-left: linear-gradient(155deg, #020617 0%, #0f172a 45%, #1e3a8a 100%);
                --al-glass-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.06) inset;
            }
        }

        /* ── Layout shell overrides ── */
        main:has(.admin-login-shell) {
            min-height: 100vh;
            max-width: none;
            margin: 0;
            padding: 0;
            display: block;
            background: var(--al-bg);
            font-family: var(--al-font);
            overflow-x: hidden;
        }

        main:has(.admin-login-shell) > .flash {
            position: fixed;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
            width: min(26rem, calc(100% - 2rem));
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
            animation: al-fade-in 0.35s ease;
        }

        /* ── Ambient background ── */
        .admin-login-shell {
            position: relative;
            min-height: 100vh;
            width: 100%;
            display: grid;
            grid-template-columns: minmax(0, 0.45fr) minmax(0, 0.55fr);
            isolation: isolate;
        }

        .admin-login-ambiance {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
            background:
                radial-gradient(ellipse 80% 60% at 10% 20%, rgba(37, 99, 235, 0.18), transparent 55%),
                radial-gradient(ellipse 70% 50% at 90% 80%, rgba(96, 165, 250, 0.14), transparent 50%),
                radial-gradient(ellipse 50% 40% at 50% 50%, rgba(59, 130, 246, 0.06), transparent 60%),
                var(--al-bg);
        }

        .admin-login-ambiance::before {
            content: '';
            position: absolute;
            inset: -20%;
            background:
                radial-gradient(circle at 20% 30%, rgba(37, 99, 235, 0.22) 0%, transparent 42%),
                radial-gradient(circle at 80% 70%, rgba(59, 130, 246, 0.18) 0%, transparent 40%),
                radial-gradient(circle at 60% 20%, rgba(96, 165, 250, 0.12) 0%, transparent 35%);
            animation: al-blob-drift 22s ease-in-out infinite alternate;
        }

        .admin-login-ambiance::after {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0.04;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cpath fill='%232563EB' d='M36 12h8v24h24v8H44v24h-8V44H12v-8h24V12z'/%3E%3C/svg%3E");
            background-size: 80px 80px;
        }

        .admin-login-particle {
            position: absolute;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--al-accent);
            opacity: 0.35;
            animation: al-particle 14s linear infinite;
        }

        .admin-login-particle:nth-child(1) { left: 12%; top: 20%; animation-delay: 0s; animation-duration: 16s; }
        .admin-login-particle:nth-child(2) { left: 28%; top: 65%; width: 4px; height: 4px; animation-delay: -4s; animation-duration: 18s; }
        .admin-login-particle:nth-child(3) { left: 72%; top: 30%; width: 5px; height: 5px; animation-delay: -8s; animation-duration: 20s; }
        .admin-login-particle:nth-child(4) { left: 88%; top: 70%; animation-delay: -2s; animation-duration: 15s; }
        .admin-login-particle:nth-child(5) { left: 50%; top: 85%; width: 3px; height: 3px; animation-delay: -6s; animation-duration: 17s; }

        /* ── Left branding panel ── */
        .admin-login-brand-panel {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: clamp(2rem, 4vw, 3.5rem);
            color: #fff;
            background: var(--al-panel-left);
            overflow: hidden;
            animation: al-fade-in 0.6s ease;
        }

        .admin-login-brand-panel::before {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(96, 165, 250, 0.35), transparent 70%);
            top: -120px;
            right: -100px;
            animation: al-float 10s ease-in-out infinite;
        }

        .admin-login-brand-panel::after {
            content: '';
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.4), transparent 70%);
            bottom: -60px;
            left: -80px;
            animation: al-float 12s ease-in-out infinite reverse;
        }

        .admin-login-brand-top,
        .admin-login-brand-mid,
        .admin-login-brand-bottom {
            position: relative;
            z-index: 1;
        }

        .admin-login-brand-logo-row {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            margin-bottom: 2.5rem;
        }

        .admin-login-brand-logo {
            width: 3.5rem;
            height: 3.5rem;
            object-fit: contain;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.12);
            padding: 0.35rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .admin-login-brand-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .admin-login-brand-icon svg {
            width: 1.75rem;
            height: 1.75rem;
        }

        .admin-login-brand-app {
            font-size: 0.9375rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            opacity: 0.95;
        }

        .admin-login-brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.25rem;
            font-size: 0.6875rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            opacity: 0.7;
        }

        .admin-login-brand-heading {
            font-size: clamp(1.75rem, 3.2vw, 2.25rem);
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.03em;
            margin-bottom: 0.875rem;
            max-width: 18ch;
        }

        .admin-login-brand-sub {
            font-size: 1rem;
            line-height: 1.65;
            opacity: 0.82;
            max-width: 32ch;
            margin-bottom: 2rem;
        }

        /* SVG illustration */
        .admin-login-illustration {
            width: 100%;
            max-width: 380px;
            margin: 0 auto 2rem;
            animation: al-float 8s ease-in-out infinite;
        }

        .admin-login-illustration svg {
            width: 100%;
            height: auto;
            display: block;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.25));
        }

        .admin-login-features {
            display: grid;
            gap: 0.75rem;
            list-style: none;
        }

        .admin-login-features li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.875rem;
            opacity: 0.9;
            line-height: 1.45;
        }

        .admin-login-feature-icon {
            flex-shrink: 0;
            width: 2rem;
            height: 2rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-login-feature-icon svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        .admin-login-brand-meta {
            margin-top: 2rem;
            font-size: 0.75rem;
            opacity: 0.55;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem 1rem;
        }

        /* ── Right form panel ── */
        .admin-login-form-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: clamp(1.5rem, 4vw, 3rem);
            position: relative;
            animation: al-slide-up 0.55s ease 0.08s both;
        }

        .admin-login-card {
            width: 100%;
            max-width: 26.5rem;
            background: var(--al-surface);
            backdrop-filter: blur(24px) saturate(160%);
            -webkit-backdrop-filter: blur(24px) saturate(160%);
            border-radius: var(--al-radius);
            border: 1px solid var(--al-border);
            box-shadow: var(--al-glass-shadow);
            padding: clamp(1.75rem, 4vw, 2.5rem);
            position: relative;
        }

        .admin-login-card.has-errors {
            animation: al-shake 0.45s ease;
        }

        .admin-login-welcome {
            margin-bottom: 1.75rem;
        }

        .admin-login-welcome h1 {
            font-size: clamp(1.5rem, 2.5vw, 1.875rem);
            font-weight: 700;
            color: var(--al-text);
            letter-spacing: -0.025em;
            line-height: 1.25;
            margin-bottom: 0.5rem;
        }

        .admin-login-welcome p {
            font-size: 0.9375rem;
            color: var(--al-muted);
            line-height: 1.6;
        }

        /* Floating label fields */
        .admin-login-field {
            margin-bottom: 1.15rem;
            position: relative;
        }

        .admin-login-field-inner {
            position: relative;
        }

        .admin-login-field-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.25rem;
            height: 1.25rem;
            color: var(--al-muted);
            pointer-events: none;
            transition: color 0.2s ease;
            z-index: 1;
        }

        .admin-login-input {
            width: 100%;
            min-height: 3.25rem;
            padding: 1.15rem 0.95rem 0.45rem 2.75rem;
            border: 1.5px solid var(--al-border);
            border-radius: 12px;
            font-size: 0.9375rem;
            font-family: inherit;
            color: var(--al-text);
            background: var(--al-input-bg);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            caret-color: var(--al-primary);
        }

        .admin-login-password-wrap .admin-login-input {
            padding-right: 3.25rem;
        }

        .admin-login-float-label {
            position: absolute;
            left: 2.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--al-muted);
            pointer-events: none;
            transition: transform 0.18s ease, font-size 0.18s ease, color 0.18s ease, top 0.18s ease;
            transform-origin: left center;
            background: transparent;
        }

        .admin-login-input:focus,
        .admin-login-input:not(:placeholder-shown),
        .admin-login-input.has-value {
            padding-top: 1.25rem;
            padding-bottom: 0.4rem;
        }

        .admin-login-input:focus ~ .admin-login-float-label,
        .admin-login-input:not(:placeholder-shown) ~ .admin-login-float-label,
        .admin-login-input.has-value ~ .admin-login-float-label {
            top: 0.55rem;
            transform: translateY(0);
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: var(--al-primary);
        }

        .admin-login-input:hover {
            border-color: #cbd5e1;
        }

        .admin-login-input:focus {
            outline: none;
            border-color: var(--al-primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.18);
        }

        .admin-login-input:focus ~ .admin-login-field-icon,
        .admin-login-field-inner:focus-within .admin-login-field-icon {
            color: var(--al-primary);
        }

        .admin-login-input[aria-invalid="true"] {
            border-color: var(--al-danger);
            animation: al-field-pulse 0.4s ease;
        }

        .admin-login-input[aria-invalid="true"]:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.18);
        }

        .admin-login-input[aria-invalid="true"] ~ .admin-login-float-label {
            color: var(--al-danger);
        }

        /* Password toggle */
        .admin-login-password-wrap {
            position: relative;
        }

        .admin-login-toggle {
            position: absolute;
            right: 0.4rem;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: var(--al-muted);
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease, background 0.2s ease, transform 0.2s ease;
            z-index: 2;
        }

        .admin-login-toggle:hover {
            color: var(--al-text);
            background: rgba(148, 163, 184, 0.15);
        }

        .admin-login-toggle:active {
            transform: translateY(-50%) scale(0.92);
        }

        .admin-login-toggle:focus {
            outline: none;
        }

        .admin-login-toggle:focus-visible {
            box-shadow: 0 0 0 2px var(--al-surface-solid), 0 0 0 4px var(--al-primary);
        }

        .admin-login-toggle svg {
            width: 1.25rem;
            height: 1.25rem;
            transition: opacity 0.2s ease, transform 0.25s ease;
        }

        .admin-login-toggle[aria-pressed="true"] {
            color: var(--al-primary);
        }

        /* Caps Lock warning */
        .admin-login-capslock {
            display: none;
            align-items: center;
            gap: 0.4rem;
            margin-top: 0.45rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #b45309;
            background: rgba(251, 191, 36, 0.12);
            border: 1px solid rgba(251, 191, 36, 0.35);
            border-radius: 8px;
            padding: 0.4rem 0.65rem;
        }

        .admin-login-capslock.is-visible {
            display: flex;
            animation: al-fade-in 0.25s ease;
        }

        .admin-login-capslock svg {
            width: 0.95rem;
            height: 0.95rem;
            flex-shrink: 0;
        }

        /* Strength indicator placeholder (future-ready) */
        .admin-login-strength {
            display: none;
            height: 3px;
            margin-top: 0.5rem;
            border-radius: 999px;
            background: rgba(148, 163, 184, 0.25);
            overflow: hidden;
        }

        .admin-login-strength-bar {
            height: 100%;
            width: 0;
            border-radius: inherit;
            background: var(--al-primary);
            transition: width 0.3s ease, background 0.3s ease;
        }

        .admin-login-error {
            color: var(--al-danger);
            font-size: 0.8125rem;
            margin-top: 0.4rem;
            display: flex;
            align-items: flex-start;
            gap: 0.35rem;
            animation: al-fade-in 0.3s ease;
        }

        .admin-login-error::before {
            content: '';
            flex-shrink: 0;
            width: 1rem;
            height: 1rem;
            margin-top: 0.1rem;
            background: currentColor;
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='black'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z'/%3E%3C/svg%3E") center / contain no-repeat;
            -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='black'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z'/%3E%3C/svg%3E") center / contain no-repeat;
        }

        /* Options row */
        .admin-login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1.35rem;
            flex-wrap: wrap;
        }

        .admin-login-remember {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--al-muted);
            cursor: pointer;
            user-select: none;
        }

        .admin-login-remember input {
            width: 1.05rem;
            height: 1.05rem;
            accent-color: var(--al-primary);
            cursor: pointer;
            border-radius: 4px;
        }

        .admin-login-forgot {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--al-primary);
            text-decoration: none;
            border-radius: 4px;
            transition: color 0.15s ease;
        }

        .admin-login-forgot:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .admin-login-forgot:focus-visible {
            outline: 2px solid var(--al-primary);
            outline-offset: 2px;
        }

        /* Submit button */
        .admin-login-submit {
            position: relative;
            width: 100%;
            min-height: 3.1rem;
            overflow: hidden;
            background: linear-gradient(135deg, var(--al-secondary) 0%, var(--al-primary) 50%, #1d4ed8 100%);
            background-size: 200% 200%;
            color: #fff;
            border: none;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.975rem;
            cursor: pointer;
            box-shadow:
                0 1px 2px rgba(15, 23, 42, 0.1),
                0 8px 20px -6px rgba(37, 99, 235, 0.55);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease, background-position 0.4s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
        }

        .admin-login-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            filter: brightness(1.05);
            background-position: 100% 0;
            box-shadow:
                0 4px 8px rgba(15, 23, 42, 0.12),
                0 14px 28px -8px rgba(37, 99, 235, 0.6);
        }

        .admin-login-submit:active:not(:disabled) {
            transform: translateY(0);
        }

        .admin-login-submit:focus {
            outline: none;
        }

        .admin-login-submit:focus-visible {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.4), 0 8px 20px -6px rgba(37, 99, 235, 0.55);
        }

        .admin-login-submit:disabled {
            cursor: not-allowed;
            opacity: 0.85;
            transform: none;
        }

        .admin-login-submit.is-loading .admin-login-submit-label {
            opacity: 0;
        }

        .admin-login-submit-spinner {
            display: none;
            position: absolute;
            width: 1.35rem;
            height: 1.35rem;
            border: 2.5px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: al-spin 0.7s linear infinite;
        }

        .admin-login-submit.is-loading .admin-login-submit-spinner {
            display: block;
        }

        /* Ripple */
        .admin-login-ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.35);
            transform: scale(0);
            animation: al-ripple 0.6s ease-out;
            pointer-events: none;
        }

        /* Footer */
        .admin-login-footer {
            margin-top: 1.75rem;
            text-align: center;
            max-width: 26.5rem;
            width: 100%;
        }

        .admin-login-footer-links {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.35rem 1rem;
            margin-bottom: 0.75rem;
            font-size: 0.8125rem;
        }

        .admin-login-footer-links a {
            color: var(--al-primary);
            font-weight: 600;
            text-decoration: none;
            border-radius: 4px;
            transition: color 0.15s ease;
        }

        .admin-login-footer-links a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .admin-login-footer-links a:focus-visible {
            outline: 2px solid var(--al-primary);
            outline-offset: 2px;
        }

        .admin-login-footer-meta {
            font-size: 0.75rem;
            color: var(--al-muted);
            line-height: 1.55;
        }

        .admin-login-footer-meta span + span::before {
            content: '·';
            margin: 0 0.4rem;
            opacity: 0.5;
        }

        /* Visually hidden (a11y) */
        .admin-login-sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* ── Keyframes ── */
        @keyframes al-fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes al-slide-up {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes al-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes al-blob-drift {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(3%, -2%) scale(1.05); }
        }

        @keyframes al-particle {
            0% { transform: translateY(0) scale(1); opacity: 0.3; }
            50% { transform: translateY(-40px) scale(1.2); opacity: 0.55; }
            100% { transform: translateY(-80px) scale(0.8); opacity: 0; }
        }

        @keyframes al-spin {
            to { transform: rotate(360deg); }
        }

        @keyframes al-ripple {
            to { transform: scale(4); opacity: 0; }
        }

        @keyframes al-shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-5px); }
            80% { transform: translateX(5px); }
        }

        @keyframes al-field-pulse {
            0%, 100% { box-shadow: none; }
            50% { box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2); }
        }

        /* ── Responsive ── */
        @media (max-width: 960px) {
            .admin-login-shell {
                grid-template-columns: 1fr;
            }

            .admin-login-brand-panel {
                min-height: auto;
                padding: 1.75rem 1.5rem 2rem;
            }

            .admin-login-brand-heading {
                max-width: none;
                font-size: 1.625rem;
            }

            .admin-login-brand-sub {
                max-width: none;
                margin-bottom: 1.25rem;
            }

            .admin-login-illustration {
                max-width: 240px;
                margin-bottom: 1.25rem;
            }

            .admin-login-features {
                display: none;
            }

            .admin-login-brand-meta {
                margin-top: 0.5rem;
            }

            .admin-login-form-panel {
                padding: 1.5rem 1.25rem 2.5rem;
            }
        }

        @media (max-width: 480px) {
            .admin-login-card {
                padding: 1.5rem 1.25rem;
                border-radius: 18px;
            }

            .admin-login-brand-logo-row {
                margin-bottom: 1.25rem;
            }

            .admin-login-options {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (min-width: 1600px) {
            .admin-login-card {
                max-width: 28rem;
            }

            .admin-login-brand-heading {
                font-size: 2.5rem;
            }
        }

        /* ── Reduced motion ── */
        @media (prefers-reduced-motion: reduce) {
            .admin-login-ambiance::before,
            .admin-login-brand-panel::before,
            .admin-login-brand-panel::after,
            .admin-login-illustration,
            .admin-login-particle,
            .admin-login-brand-panel,
            .admin-login-form-panel,
            .admin-login-card.has-errors,
            .admin-login-input[aria-invalid="true"],
            main:has(.admin-login-shell) > .flash {
                animation: none !important;
            }

            .admin-login-input,
            .admin-login-toggle,
            .admin-login-submit,
            .admin-login-float-label,
            .admin-login-field-icon,
            .admin-login-footer-links a {
                transition: none !important;
            }

            .admin-login-submit:hover:not(:disabled) {
                transform: none;
            }

            .admin-login-submit:active:not(:disabled) {
                transform: none;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $brandLogo = file_exists(public_path('logo.png')) ? asset('public/logo.png') : null;
        $hasErrors = $errors->has('username') || $errors->has('password');
        $hasForgotRoute = \Illuminate\Support\Facades\Route::has('password.request')
            || \Illuminate\Support\Facades\Route::has('admin.password.request');
    @endphp

    <div class="admin-login-shell">
        <div class="admin-login-ambiance" aria-hidden="true">
            <span class="admin-login-particle"></span>
            <span class="admin-login-particle"></span>
            <span class="admin-login-particle"></span>
            <span class="admin-login-particle"></span>
            <span class="admin-login-particle"></span>
        </div>

        {{-- Left: Branding --}}
        <aside class="admin-login-brand-panel" aria-label="{{ __('Application branding') }}">
            <div class="admin-login-brand-top">
                <div class="admin-login-brand-logo-row">
                    @if ($brandLogo)
                        <img class="admin-login-brand-logo" src="{{ $brandLogo }}" alt="{{ config('app.name') }}" width="56" height="56">
                    @else
                        <div class="admin-login-brand-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                        </div>
                    @endif
                    <div>
                        <div class="admin-login-brand-app">{{ config('app.name') }}</div>
                        <div class="admin-login-brand-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21" />
                            </svg>
                            {{ __('Healthcare Administration') }}
                        </div>
                    </div>
                </div>

                <h2 class="admin-login-brand-heading">{{ __('Healthcare Management System') }}</h2>
                <p class="admin-login-brand-sub">{{ __('Secure access for administrators, doctors, and healthcare personnel.') }}</p>
            </div>

            <div class="admin-login-brand-mid">
                <div class="admin-login-illustration" aria-hidden="true">
                    {{-- Custom medical dashboard SVG (original artwork) --}}
                    <svg viewBox="0 0 420 280" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true">
                        {{-- Dashboard frame --}}
                        <rect x="40" y="28" width="340" height="220" rx="20" fill="rgba(255,255,255,0.12)" stroke="rgba(255,255,255,0.28)" stroke-width="1.5"/>
                        <rect x="56" y="44" width="120" height="14" rx="7" fill="rgba(255,255,255,0.35)"/>
                        <rect x="56" y="68" width="72" height="8" rx="4" fill="rgba(255,255,255,0.18)"/>

                        {{-- Stat cards --}}
                        <rect x="56" y="96" width="92" height="64" rx="12" fill="rgba(255,255,255,0.14)" stroke="rgba(255,255,255,0.2)"/>
                        <circle cx="76" cy="118" r="10" fill="#60A5FA" opacity="0.9"/>
                        <rect x="92" y="112" width="40" height="6" rx="3" fill="rgba(255,255,255,0.45)"/>
                        <rect x="92" y="124" width="28" height="5" rx="2.5" fill="rgba(255,255,255,0.25)"/>
                        <rect x="68" y="140" width="56" height="8" rx="4" fill="#34D399" opacity="0.7"/>

                        <rect x="164" y="96" width="92" height="64" rx="12" fill="rgba(255,255,255,0.14)" stroke="rgba(255,255,255,0.2)"/>
                        <circle cx="184" cy="118" r="10" fill="#A78BFA" opacity="0.9"/>
                        <rect x="200" y="112" width="40" height="6" rx="3" fill="rgba(255,255,255,0.45)"/>
                        <rect x="200" y="124" width="28" height="5" rx="2.5" fill="rgba(255,255,255,0.25)"/>
                        <rect x="176" y="140" width="44" height="8" rx="4" fill="#60A5FA" opacity="0.7"/>

                        <rect x="272" y="96" width="92" height="64" rx="12" fill="rgba(255,255,255,0.14)" stroke="rgba(255,255,255,0.2)"/>
                        <circle cx="292" cy="118" r="10" fill="#34D399" opacity="0.9"/>
                        <rect x="308" y="112" width="40" height="6" rx="3" fill="rgba(255,255,255,0.45)"/>
                        <rect x="308" y="124" width="28" height="5" rx="2.5" fill="rgba(255,255,255,0.25)"/>
                        <rect x="284" y="140" width="52" height="8" rx="4" fill="#FBBF24" opacity="0.7"/>

                        {{-- Chart area --}}
                        <rect x="56" y="176" width="200" height="54" rx="12" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.18)"/>
                        <path d="M72 214 C92 210, 100 198, 120 200 C140 202, 148 188, 168 190 C188 192, 196 204, 216 198 C228 194, 236 200, 240 196" stroke="#93C5FD" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                        <circle cx="168" cy="190" r="3.5" fill="#fff"/>

                        {{-- Shield badge --}}
                        <g transform="translate(290, 178)">
                            <rect width="74" height="52" rx="12" fill="rgba(16,185,129,0.2)" stroke="rgba(52,211,153,0.45)"/>
                            <path d="M37 12c0 0-10 3-10 12 0 8 6.5 13 10 15 3.5-2 10-7 10-15 0-9-10-12-10-12z" fill="#34D399" opacity="0.9"/>
                            <path d="M33 24.5l3 3 5.5-5.5" stroke="#064E3B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        </g>

                        {{-- Floating medical cross --}}
                        <g transform="translate(360, 36)" opacity="0.85">
                            <circle cx="18" cy="18" r="18" fill="rgba(255,255,255,0.15)"/>
                            <path d="M15 8h6v7h7v6h-7v7h-6v-7H8v-6h7V8z" fill="#fff"/>
                        </g>
                    </svg>
                </div>

                <ul class="admin-login-features">
                    <li>
                        <span class="admin-login-feature-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                        </span>
                        <span>{{ __('Encrypted sessions and role-based access control') }}</span>
                    </li>
                    <li>
                        <span class="admin-login-feature-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </span>
                        <span>{{ __('Patient records, certificates, and referrals in one place') }}</span>
                    </li>
                    <li>
                        <span class="admin-login-feature-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                        </span>
                        <span>{{ __('Real-time clinical insights for informed decisions') }}</span>
                    </li>
                </ul>
            </div>

            <div class="admin-login-brand-bottom">
                <div class="admin-login-brand-meta">
                    <span>{{ __('Enterprise-grade security') }}</span>
                    <span>{{ __('HIPAA-conscious design') }}</span>
                </div>
            </div>
        </aside>

        {{-- Right: Login form --}}
        <div class="admin-login-form-panel">
            <div class="admin-login-card{{ $hasErrors ? ' has-errors' : '' }}">
                <header class="admin-login-welcome">
                    <h1>{{ __('Welcome Back') }}</h1>
                    <p>{{ __('Sign in to continue managing patients, medical certificates, referrals, and healthcare records securely.') }}</p>
                </header>

                <form id="admin-login-form" method="post" action="{{ route('admin.login.store') }}">
                    @csrf

                    <div class="admin-login-field">
                        <div class="admin-login-field-inner">
                            <svg class="admin-login-field-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <input
                                id="username"
                                class="admin-login-input{{ old('username') ? ' has-value' : '' }}"
                                name="username"
                                type="text"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder=" "
                                aria-required="true"
                                @error('username') aria-invalid="true" @enderror
                                @error('username') aria-describedby="username-error" @enderror
                            >
                            <label class="admin-login-float-label" for="username">{{ __('Username') }}</label>
                        </div>
                        @error('username')
                            <p class="admin-login-error" id="username-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="admin-login-field">
                        <div class="admin-login-field-inner admin-login-password-wrap">
                            <svg class="admin-login-field-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <input
                                id="password"
                                class="admin-login-input"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder=" "
                                aria-required="true"
                                @error('password') aria-invalid="true" @enderror
                                @error('password') aria-describedby="password-error" @enderror
                            >
                            <label class="admin-login-float-label" for="password">{{ __('Password') }}</label>
                            <button
                                type="button"
                                class="admin-login-toggle"
                                id="admin-login-toggle-password"
                                aria-pressed="false"
                                aria-label="{{ __('Show password') }}"
                            >
                                <span class="admin-login-icon-show" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </span>
                                <span class="admin-login-icon-hide" hidden aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243 4.242M9.88 9.88l4.24 4.24" />
                                    </svg>
                                </span>
                            </button>
                        </div>

                        {{-- Future-ready strength meter (hidden until wired) --}}
                        <div class="admin-login-strength" aria-hidden="true">
                            <div class="admin-login-strength-bar"></div>
                        </div>

                        <div
                            id="admin-login-capslock"
                            class="admin-login-capslock"
                            role="status"
                            aria-live="polite"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            {{ __('Caps Lock is On') }}
                        </div>

                        @error('password')
                            <p class="admin-login-error" id="password-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="admin-login-options">
                        <label class="admin-login-remember">
                            <input type="checkbox" name="remember" value="1" autocomplete="off">
                            <span>{{ __('Remember this device') }}</span>
                        </label>

                        @if ($hasForgotRoute)
                            <a
                                class="admin-login-forgot"
                                href="{{ \Illuminate\Support\Facades\Route::has('admin.password.request') ? route('admin.password.request') : route('password.request') }}"
                            >
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="admin-login-submit" id="admin-login-submit">
                        <span class="admin-login-submit-spinner" aria-hidden="true"></span>
                        <span class="admin-login-submit-label">{{ __('Sign In') }}</span>
                    </button>
                </form>

            </div>

            <footer class="admin-login-footer">
                <div class="admin-login-footer-links">
                    <a href="{{ url('/') }}">{{ __('Back to home') }}</a>
                </div>
                <p class="admin-login-footer-meta">
                    <span>&copy; {{ date('Y') }} Western Philippines University</span>
                    <span>{{ __('Healthcare Management System') }}</span>
                    <span>{{ __('Version 2.0') }}</span>
                </p>
            </footer>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var password = document.getElementById('password');
            var toggle = document.getElementById('admin-login-toggle-password');
            var form = document.getElementById('admin-login-form');
            var submitBtn = document.getElementById('admin-login-submit');
            var username = document.getElementById('username');
            var capsLockEl = document.getElementById('admin-login-capslock');
            var card = document.querySelector('.admin-login-card');

            var showIcon = toggle ? toggle.querySelector('.admin-login-icon-show') : null;
            var hideIcon = toggle ? toggle.querySelector('.admin-login-icon-hide') : null;
            var labelShow = @json(__('Show password'));
            var labelHide = @json(__('Hide password'));
            var isSubmitting = false;

            // Auto-focus username if empty (complements autofocus attribute)
            if (username && !username.value) {
                try { username.focus(); } catch (e) {}
            }

            // Keep floating labels in sync for prefilled values
            function syncFloatState(input) {
                if (!input) return;
                if (input.value && input.value.length) {
                    input.classList.add('has-value');
                } else {
                    input.classList.remove('has-value');
                }
            }
            syncFloatState(username);
            syncFloatState(password);
            if (username) {
                username.addEventListener('input', function () { syncFloatState(username); });
            }
            if (password) {
                password.addEventListener('input', function () { syncFloatState(password); });
            }

            // Password visibility toggle (preserved IDs & behavior)
            if (password && toggle) {
                toggle.addEventListener('click', function () {
                    var isHidden = password.type === 'password';
                    password.type = isHidden ? 'text' : 'password';
                    toggle.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                    toggle.setAttribute('aria-label', isHidden ? labelHide : labelShow);
                    if (showIcon && hideIcon) {
                        showIcon.hidden = isHidden;
                        hideIcon.hidden = !isHidden;
                    }
                });
            }

            // Caps Lock detection
            function updateCapsLock(event) {
                if (!capsLockEl || !password) return;
                var on = false;
                try {
                    if (event && typeof event.getModifierState === 'function') {
                        on = event.getModifierState('CapsLock');
                    }
                } catch (e) {}
                capsLockEl.classList.toggle('is-visible', on);
            }

            if (password) {
                password.addEventListener('keydown', updateCapsLock);
                password.addEventListener('keyup', updateCapsLock);
                password.addEventListener('focus', updateCapsLock);
                password.addEventListener('blur', function () {
                    if (capsLockEl) capsLockEl.classList.remove('is-visible');
                });
            }

            // Submit: loading state, ripple, prevent double-submit
            if (form && submitBtn) {
                form.addEventListener('submit', function (e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        return;
                    }
                    isSubmitting = true;
                    submitBtn.classList.add('is-loading');
                    submitBtn.disabled = true;
                    submitBtn.setAttribute('aria-busy', 'true');
                });

                submitBtn.addEventListener('click', function (e) {
                    var rect = submitBtn.getBoundingClientRect();
                    var size = Math.max(rect.width, rect.height);
                    var ripple = document.createElement('span');
                    ripple.className = 'admin-login-ripple';
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
                    ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
                    submitBtn.appendChild(ripple);
                    setTimeout(function () {
                        if (ripple.parentNode) ripple.parentNode.removeChild(ripple);
                    }, 650);
                });
            }

            // Re-trigger shake if validation errors present after paint
            if (card && card.classList.contains('has-errors')) {
                card.classList.remove('has-errors');
                void card.offsetWidth;
                card.classList.add('has-errors');
            }
        })();
    </script>
@endpush
