/**
 * NUTRITION THEME SWITCHER - VERSÃO COMPLETA
 * Gerenciamento de tema claro/escuro para o sistema de nutrição
 * Compatível com Bootstrap 5 e AdminLTE 3.2
 */

(function() {
    'use strict';

    // Configurações
    const THEME_KEY = 'nutrition-theme';
    const THEMES = {
        LIGHT: 'light',
        DARK: 'dark'
    };

    /**
     * Classe principal para gerenciamento de temas
     */
    class ThemeManager {
        constructor() {
            this.currentTheme = this.getSavedTheme() || THEMES.DARK; // Padrão: dark
            this.init();
        }

        /**
         * Inicializa o gerenciador de temas
         */
        init() {
            this.applyTheme(this.currentTheme);
            this.setupEventListeners();
            // Aguarda o carregamento completo do DOM
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.createThemeToggle());
            } else {
                this.createThemeToggle();
            }
        }

        /**
         * Obtém o tema salvo do localStorage
         */
        getSavedTheme() {
            try {
                return localStorage.getItem(THEME_KEY);
            } catch (e) {
                console.warn('localStorage não disponível:', e);
                return null;
            }
        }

        /**
         * Salva o tema no localStorage
         */
        saveTheme(theme) {
            try {
                localStorage.setItem(THEME_KEY, theme);
            } catch (e) {
                console.warn('Não foi possível salvar o tema:', e);
            }
        }

        /**
         * Aplica o tema ao documento
         */
        applyTheme(theme) {
            const html = document.documentElement;
            html.setAttribute('data-theme', theme);
            this.currentTheme = theme;
            this.saveTheme(theme);
            
            // Atualiza o botão se já existir
            this.updateToggleButton();
            
            console.log('Tema aplicado:', theme);
        }

        /**
         * Alterna entre temas claro e escuro
         */
        toggleTheme() {
            const newTheme = this.currentTheme === THEMES.LIGHT ? THEMES.DARK : THEMES.LIGHT;
            this.applyTheme(newTheme);
        }

        /**
         * Cria o botão de toggle de tema
         */
        createThemeToggle() {
            // Verifica se o botão já existe
            if (document.getElementById('theme-toggle-btn')) {
                console.log('Botão de tema já existe');
                return;
            }

            // Tenta encontrar a navbar
            let navbar = document.querySelector('.navbar-nav.navbar-close');
            
            if (!navbar) {
                navbar = document.querySelector('.navbar-nav');
            }

            if (!navbar) {
                console.warn('Navbar não encontrada, tentando novamente em 500ms...');
                setTimeout(() => this.createThemeToggle(), 500);
                return;
            }

            console.log('Navbar encontrada, criando botão de tema');

            // Cria o item do menu
            const themeToggleItem = document.createElement('li');
            themeToggleItem.className = 'nav-item';
            themeToggleItem.id = 'theme-toggle-container';
            themeToggleItem.style.cssText = `
                margin-left: 10px;
                display: flex;
                align-items: center;
            `;

            // Cria o botão
            const themeToggleBtn = document.createElement('button');
            themeToggleBtn.id = 'theme-toggle-btn';
            themeToggleBtn.className = 'btn btn-sm nav-link';
            themeToggleBtn.type = 'button';
            themeToggleBtn.setAttribute('aria-label', 'Alternar tema');
            themeToggleBtn.style.cssText = `
                border: none;
                background: transparent;
                padding: 8px 14px;
                border-radius: 8px;
                transition: all 0.3s ease;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 14px;
                font-weight: 500;
                color: inherit;
            `;
            
            // Conteúdo do botão
            themeToggleBtn.innerHTML = `
                <i class="fas fa-moon" id="theme-icon" style="font-size: 16px;"></i>
                <span id="theme-text" style="display: inline-block; min-width: 50px; text-align: left;">Escuro</span>
            `;

            // Event listener para alternar tema
            themeToggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.toggleTheme();
            });

            // Efeitos de hover
            themeToggleBtn.addEventListener('mouseenter', function() {
                this.style.background = 'rgba(46, 204, 113, 0.15)';
                this.style.transform = 'scale(1.05)';
            });

            themeToggleBtn.addEventListener('mouseleave', function() {
                this.style.background = 'transparent';
                this.style.transform = 'scale(1)';
            });

            // Adiciona o botão ao item
            themeToggleItem.appendChild(themeToggleBtn);
            
            // Adiciona à navbar
            navbar.appendChild(themeToggleItem);

            console.log('Botão de tema criado com sucesso');
            
            // Atualiza o visual do botão
            this.updateToggleButton();
        }

        /**
         * Atualiza o visual do botão de toggle
         */
        updateToggleButton() {
            const icon = document.getElementById('theme-icon');
            const text = document.getElementById('theme-text');

            if (!icon || !text) {
                return;
            }

            if (this.currentTheme === THEMES.DARK) {
                icon.className = 'fas fa-sun';
                text.textContent = 'Claro';
            } else {
                icon.className = 'fas fa-moon';
                text.textContent = 'Escuro';
            }
        }

        /**
         * Configura event listeners
         */
        setupEventListeners() {
            // Detecta mudanças na preferência do sistema
            if (window.matchMedia) {
                const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
                
                darkModeQuery.addEventListener('change', (e) => {
                    // Só aplica se não houver preferência salva
                    if (!this.getSavedTheme()) {
                        const newTheme = e.matches ? THEMES.DARK : THEMES.LIGHT;
                        this.applyTheme(newTheme);
                    }
                });
            }

            // Reaplica o tema ao carregar a página
            window.addEventListener('load', () => {
                this.applyTheme(this.currentTheme);
            });
        }

        /**
         * Obtém o tema atual
         */
        getCurrentTheme() {
            return this.currentTheme;
        }

        /**
         * Define um tema específico
         */
        setTheme(theme) {
            if (Object.values(THEMES).includes(theme)) {
                this.applyTheme(theme);
            } else {
                console.error('Tema inválido:', theme);
            }
        }
    }

    // Inicializa o gerenciador de temas
    const themeManager = new ThemeManager();

    // Exporta para uso global
    window.nutritionTheme = themeManager;
    window.NutritionThemes = THEMES;

    console.log('Nutrition Theme Manager inicializado');

})();