@if(session('success'))
    <div class="alert alert-success">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        <span>{{ session('success') }}</span>
    </div>

    <!-- Pop-up Modal Dialog Sesuai Revisi Guru -->
    <div class="popup-modal-overlay" id="flashPopupModalSuccess" onclick="if(event.target === this) this.style.display='none';">
        <div class="popup-modal-card">
            <div class="popup-modal-icon success">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h3 class="popup-modal-title">Berhasil Disimpan</h3>
            <p class="popup-modal-message">{{ session('success') }}</p>
            <button type="button" class="popup-modal-btn" onclick="document.getElementById('flashPopupModalSuccess').style.display='none';">
                Mengerti (Tutup)
            </button>
        </div>
    </div>
@endif

@if(session('error') || (isset($errors) && $errors->any()))
    @php
        $errorMessage = session('error') ?? (isset($errors) && $errors->any() ? $errors->first() : '');
    @endphp
    <div class="alert alert-error">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="8" x2="12" y2="12"></line>
            <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <span>{{ $errorMessage }}</span>
    </div>

    <!-- Pop-up Modal Dialog Sesuai Revisi Guru -->
    <div class="popup-modal-overlay" id="flashPopupModalError" onclick="if(event.target === this) this.style.display='none';">
        <div class="popup-modal-card">
            <div class="popup-modal-icon error">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <h3 class="popup-modal-title">Peringatan / Gagal</h3>
            <p class="popup-modal-message">{{ $errorMessage }}</p>
            <button type="button" class="popup-modal-btn" onclick="document.getElementById('flashPopupModalError').style.display='none';">
                Mengerti (Tutup)
            </button>
        </div>
    </div>
@endif

<script>
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const m1 = document.getElementById('flashPopupModalSuccess');
            const m2 = document.getElementById('flashPopupModalError');
            if (m1) m1.style.display = 'none';
            if (m2) m2.style.display = 'none';
        }
    });
</script>
