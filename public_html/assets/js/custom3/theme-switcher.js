document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtn = document.getElementById('theme-toggle');
    const currentTheme = localStorage.getItem('theme') || 'light';

    // Aplica o tema salvo ao carregar a página
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-theme');
        if(themeToggleBtn) themeToggleBtn.innerHTML = '<i class="fas fa-sun"></i>';
    }

    // Lógica do clique no botão
    if(themeToggleBtn) {
        themeToggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            document.body.classList.toggle('dark-theme');
            
            let theme = 'light';
            if (document.body.classList.contains('dark-theme')) {
                theme = 'dark';
                themeToggleBtn.innerHTML = '<i class="fas fa-sun"></i>'; // Ícone de Sol pro Dark
            } else {
                themeToggleBtn.innerHTML = '<i class="fas fa-moon"></i>'; // Ícone de Lua pro Light
            }
            
            // Salva a escolha no navegador
            localStorage.setItem('theme', theme);
        });
    }
});