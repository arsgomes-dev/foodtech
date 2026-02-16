// nutrition-wizard-init.js
// Initializes NutritionWizard and chart; moved from inline script in home.php
document.addEventListener('DOMContentLoaded', function(){
    // Initialize chart if present
    const ctx = document.getElementById('caloriesChart');
    if (ctx && typeof Chart !== 'undefined'){
        try{
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                    datasets: [{
                        label: 'Calorias Consumidas',
                        data: [1800, 2100, 1950, 1850, 2000, 1900, 1850],
                        borderColor: getComputedStyle(document.documentElement).getPropertyValue('--chart-series-1') || '#2ecc71',
                        backgroundColor: 'rgba(46, 204, 113, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--chart-series-1') || '#2ecc71'
                    }, {
                        label: 'Meta Diária',
                        data: [2000,2000,2000,2000,2000,2000,2000],
                        borderColor: getComputedStyle(document.documentElement).getPropertyValue('--chart-meta') || '#95a5a6',
                        borderWidth: 2,
                        borderDash: [5,5],
                        fill: false,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: true, position: 'top' }, tooltip: { mode: 'index' } },
                    scales: { y: { beginAtZero:true } }
                }
            });
        }catch(e){ console.error('Chart init error', e); }
    }

    // Initialize NutritionWizard if available
    const modalEl = document.getElementById('nutriModal');
    if(modalEl){
        if(typeof NutritionWizard === 'function'){
            try{ window.__nutriWizard = new NutritionWizard('#nutriModal'); }catch(e){ console.error('NutritionWizard init error', e); }
        }
        // Close button
        const closeBtn = modalEl.querySelector('#nutriClose');
        if(closeBtn){ closeBtn.addEventListener('click', function(){ modalEl.style.display = 'none'; try{ delete window.__nutriWizard; }catch(e){} }); }
        // ESC key closes modal
        document.addEventListener('keydown', function(e){ if(e.key === 'Escape'){ if(modalEl.style.display !== 'none'){ modalEl.style.display = 'none'; } } });
    }
});
