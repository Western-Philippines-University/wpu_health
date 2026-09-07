@extends('layouts.admin')

@section('title', __('Dashboard'))

@section('content')
    <h1 style="font-size:1.35rem;margin-bottom:0.35rem;">{{ __('Unified admin dashboard') }}</h1>
    <p style="color:var(--muted);font-size:0.9rem;margin-bottom:1.5rem;">
        {{ __('Signed in as :user.', ['user' => $admin->username]) }}
    </p>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(11rem,1fr));gap:0.75rem;margin-bottom:1.5rem;">
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:1rem;">
            <div style="font-size:1.5rem;font-weight:700;color:var(--primary);">{{ number_format($stats['medical_certificates']) }}</div>
            <div style="font-size:0.8125rem;color:var(--muted);">{{ __('Medical certificates') }}</div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:1rem;">
            <div style="font-size:1.5rem;font-weight:700;color:#059669;">{{ number_format($stats['referrals']) }}</div>
            <div style="font-size:0.8125rem;color:var(--muted);">{{ __('Referrals') }}</div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:1rem;">
            <div style="font-size:1.5rem;font-weight:700;color:#d97706;">{{ number_format($stats['patient_records']) }}</div>
            <div style="font-size:0.8125rem;color:var(--muted);">{{ __('Patient records') }}</div>
        </div>
    </div>

    <section style="display:grid;gap:1rem;">
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.25rem;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
            <h2 style="font-size:1rem;margin-bottom:0.5rem;color:var(--primary);">{{ __('Unified clinic panel') }}</h2>
            <p style="font-size:0.875rem;color:var(--muted);margin-bottom:1rem;">
                {{ __('Full admin tools (records, certificates, referrals, reports, chat) run here under Laravel session protection.') }}
            </p>
            <a href="{{ route('admin.workspace', ['path' => 'admin/admin.php']) }}" style="display:inline-block;background:var(--primary);color:#fff;padding:0.5rem 1rem;border-radius:8px;text-decoration:none;font-weight:600;font-size:0.875rem;">
                {{ __('Open unified admin') }}
            </a>
        </div>

        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:1.25rem;">
            <h2 style="font-size:1rem;margin-bottom:0.5rem;color:#1e40af;">{{ __('Native Laravel features') }}</h2>
            <p style="font-size:0.875rem;color:#1e3a8a;">
                {{ __('Additional first-class Laravel pages (APIs, SPA modules) can be added alongside the unified workspace without moving the whole panel.') }}
            </p>
        </div>
    </section>
@endsection
