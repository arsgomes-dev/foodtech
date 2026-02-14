document.addEventListener('DOMContentLoaded', () => {

    // --- CONFIGURAÇÕES ---
    const STORAGE_KEY = 'youtubeos_cookie_consent';
    const GOOGLE_ANALYTICS_ID = 'G-JTHPV6SJ43'; // Seu ID real

    // --- ELEMENTOS DO DOM ---
    const banner = document.getElementById('cookie-banner');
    const modalElement = document.getElementById('cookieModal');
    
    // Botões
    const btnAcceptAll = document.getElementById('btn-accept-all');
    const btnRejectAll = document.getElementById('btn-reject-all');
    const btnManage = document.getElementById('btn-manage');
    const btnSavePrefs = document.getElementById('btn-save-preferences');
    const btnReopen = document.getElementById('btn-reopen-cookies');

    // Checkboxes do Modal
    const checkAnalytics = document.getElementById('check-analytics');
    const checkMarketing = document.getElementById('check-marketing');

    // Inicializa o Modal do Bootstrap
    // (Verifique se o bootstrap.bundle.min.js está carregado na página antes deste script)
    let bsModal = null;
    if (modalElement) {
        bsModal = new bootstrap.Modal(modalElement);
    }

    // --- INICIALIZAÇÃO ---
    
    // 1. Verifica se já existe consentimento salvo
    const savedConsent = localStorage.getItem(STORAGE_KEY);

    if (!savedConsent) {
        // Se NÃO tem, mostra o banner após 1 segundo
        setTimeout(() => {
            if(banner) banner.classList.remove('d-none');
        }, 1000);
    } else {
        // Se JÁ tem, aplica os scripts e mostra o botão de reabrir
        const consentData = JSON.parse(savedConsent);
        applyCookies(consentData);
        if(btnReopen) btnReopen.classList.remove('d-none');
        loadPreferences(consentData);
    }

    // --- EVENT LISTENERS ---

    // 2. Aceitar Tudo
    if (btnAcceptAll) {
        btnAcceptAll.addEventListener('click', () => {
            saveConsent({
                essential: true,
                analytics: true,
                marketing: true,
                timestamp: new Date().toISOString()
            });
            hideBanner();
        });
    }

    // 3. Rejeitar (Apenas Essenciais)
    if (btnRejectAll) {
        btnRejectAll.addEventListener('click', () => {
            saveConsent({
                essential: true,
                analytics: false,
                marketing: false,
                timestamp: new Date().toISOString()
            });
            hideBanner();
        });
    }

    // 4. Abrir Modal de Preferências
    if (btnManage) {
        btnManage.addEventListener('click', () => {
            if(bsModal) bsModal.show();
        });
    }

    // 5. Salvar Preferências do Modal
    if (btnSavePrefs) {
        btnSavePrefs.addEventListener('click', () => {
            saveConsent({
                essential: true,
                analytics: checkAnalytics ? checkAnalytics.checked : false,
                marketing: checkMarketing ? checkMarketing.checked : false,
                timestamp: new Date().toISOString()
            });
            if(bsModal) bsModal.hide();
            hideBanner();
        });
    }

    // 6. Reabrir Menu (Botão Flutuante)
    if (btnReopen) {
        btnReopen.addEventListener('click', () => {
            const currentData = JSON.parse(localStorage.getItem(STORAGE_KEY));
            if (currentData) loadPreferences(currentData);
            if(bsModal) bsModal.show();
        });
    }

    // --- FUNÇÕES ---

    function saveConsent(data) {
        // 1. Salva no LocalStorage (para o JS ler rápido)
        localStorage.setItem(STORAGE_KEY, JSON.stringify(data));

        // 2. Salva em Cookie (para o PHP ler)
        const date = new Date();
        date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000)); // 365 dias
        const expires = "expires=" + date.toUTCString();
        
        // Codifica o JSON para não quebrar o formato do cookie
        const cookieValue = encodeURIComponent(JSON.stringify(data));
        
        // Define o cookie acessível em todo o site (path=/)
        document.cookie = "youtubeos_privacy=" + cookieValue + ";" + expires + ";path=/;SameSite=Lax";

        // Aplica as mudanças visualmente e logicamente
        applyCookies(data);
        if(btnReopen) btnReopen.classList.remove('d-none');
        
        // Opcional: Recarregar para o PHP pegar o cookie imediatamente
        // window.location.reload(); 
    }

    function hideBanner() {
        if(banner) banner.classList.add('d-none');
    }

    function loadPreferences(data) {
        if (checkAnalytics) checkAnalytics.checked = data.analytics;
        if (checkMarketing) checkMarketing.checked = data.marketing;
    }

    function applyCookies(data) {
        console.log('Aplicando preferências de cookies:', data);

        // --- GOOGLE ANALYTICS ---
        if (data.analytics) {
            // Verifica se o gtag já existe (seja pelo PHP ou injeção anterior)
            if (!window.gtag) {
                console.log('Injetando Analytics via JS...');

                // 1. Cria a tag <script>
                const script = document.createElement('script');
                script.async = true;
                script.src = 'https://www.googletagmanager.com/gtag/js?id=' + GOOGLE_ANALYTICS_ID;
                document.head.appendChild(script);

                // 2. Inicializa o dataLayer
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', GOOGLE_ANALYTICS_ID);
                
                // Exponha o gtag para o window para não duplicar depois
                window.gtag = gtag;
            }
        }

        // --- MARKETING (PIXEL) ---
        if (data.marketing) {
            if (!window.fbq) {
                console.log('Injetando Pixel via JS (Placeholder)...');
                // Aqui entraria o código do Facebook Pixel se você tiver
            }
        }
    }

});