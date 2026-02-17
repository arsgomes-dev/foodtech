(function(window, $){
    'use strict';
    // Encapsula o JS do wizard para mantê-lo separado
    function NutritionWizard(selectorModal){
        this.modal = document.querySelector(selectorModal);
        if(!this.modal) return;
        this.steps = Array.from(this.modal.querySelectorAll('.step'));
        this.dots = Array.from(this.modal.querySelectorAll('#nutriStepper .dot'));
        this.current = 0;
        this.init();
    }
    NutritionWizard.prototype.showStep = function(index){
        // toggle active class on step content
        this.steps.forEach(s=>s.classList.remove('active'));
        this.steps[index].classList.add('active');
        // toggle active on inner dot circles
        this.dots.forEach(d=>d.classList.remove('active'));
        if(this.dots[index]) this.dots[index].classList.add('active');
        // update aria-selected on step-dot containers
        const stepDots = Array.from(this.modal.querySelectorAll('.step-dot'));
        stepDots.forEach((sd,i)=> sd.setAttribute('aria-selected', i===index ? 'true' : 'false'));
        // buttons visibility
        this.modal.querySelector('#nutriBack').style.display = index===0? 'none':'inline-block';
        this.modal.querySelector('#nutriNext').style.display = index===this.steps.length-1? 'none':'inline-block';
        this.modal.querySelector('#nutriSave').style.display = index===this.steps.length-1? 'inline-block':'none';
    };
    NutritionWizard.prototype.validateStep = function(index){
        if(index===0){
            const h = this.modal.querySelector('#height').value;
            const w = this.modal.querySelector('#weight').value;
            if(!h || h<50 || h>300){ window.alert('Informe uma altura válida (cm).'); return false; }
            if(!w || w<10 || w>500){ window.alert('Informe um peso válido (kg).'); return false; }
            return true;
        }
        if(index===1){
            const selected = this.modal.querySelector('.nutri-option.selected');
            if(!selected){ window.alert('Selecione um nível de atividade física.'); return false; }
            return true;
        }
        if(index===2){
            const selected = this.modal.querySelector('.nutri-option.selected');
            if(!selected){ window.alert('Selecione sua meta.'); return false; }
            return true;
        }
        return true;
    };
    NutritionWizard.prototype.calculateResults = function(){
        const sex = (this.modal.querySelector('input[name="sex"]:checked')||{}).value || 'M';
        const height = parseFloat(this.modal.querySelector('#height').value);
        const weight = parseFloat(this.modal.querySelector('#weight').value);
        const activityNode = this.modal.querySelector('.step[data-step="2"] .nutri-option.selected');
        const activity_id = activityNode ? activityNode.getAttribute('data-value') : null;
        const activity_multiplier = activityNode ? parseFloat(activityNode.getAttribute('data-multiplier')) : null;
        const goalNode = this.modal.querySelector('.step[data-step="3"] .nutri-option.selected');
        const goal_id = goalNode ? goalNode.getAttribute('data-value') : null;
        const goal_caloric_adjustment = goalNode ? parseFloat(goalNode.getAttribute('data-caloric-adjustment')) : 0;
        const goal_protein = goalNode ? parseFloat(goalNode.getAttribute('data-protein')) : null;
        const goal_carbs = goalNode ? parseFloat(goalNode.getAttribute('data-carbs')) : null;
        const goal_fat = goalNode ? parseFloat(goalNode.getAttribute('data-fat')) : null;

        const clientBirth = this.modal.dataset.clientBirth || '';
        // calcular idade
        let age = 30;
        if(clientBirth){
            const parts = clientBirth.split('-');
            if(parts.length>=3){
                const b = new Date(parts[0], parts[1]-1, parts[2]);
                if(!isNaN(b.getTime())){
                    const today = new Date();
                    age = today.getFullYear() - b.getFullYear();
                    const m = today.getMonth() - b.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < b.getDate())) age--;
                }
            }
        }

        // IMC
        const imc = (weight / ((height/100)*(height/100)));
        const imcFixed = imc.toFixed(1);
        this.modal.querySelector('#resultImc').innerText = imcFixed;
        let imcLabel = 'Normal';
        if(imc<18.5) imcLabel='Abaixo do peso';
        else if(imc<25) imcLabel='Normal';
        else if(imc<30) imcLabel='Sobrepeso';
        else if(imc<40) imcLabel='Obesidade'; else imcLabel='Obesidade Grau III';
        this.modal.querySelector('#resultImcLabel').innerText = imcLabel;

        // Peso ideal (WHO): IMC ideal 22 homens, 21 mulheres
        const ideal_bmi = (sex==='M')?22:21;
        const height_m = height/100;
        const weight_ideal = ideal_bmi * (height_m * height_m);

        // decide se usar peso ajustado: obesidade (IMC>30) ou desnutricao (IMC<18)
        let usedWeight = weight;
        let usedWeightNote = '';
        let usedWeightType = '';
        if(imc > 30){
            // obesidade -> peso ajustado
            usedWeight = ((weight - weight_ideal) * 0.25) + weight_ideal;
            usedWeightNote = 'Peso ajustado aplicado (IMC > 30) para cálculo mais preciso.';
            usedWeightType = 'obesity';
        } else if(imc < 18){
            // desnutrição -> peso ajustado
            usedWeight = ((weight_ideal - weight) * 0.25) + weight;
            usedWeightNote = 'Peso ajustado aplicado (IMC < 18) para cálculo mais preciso.';
            usedWeightType = 'underweight';
        }

        // Harris-Benedict revisada (1984)
        let tmb = 0;
        if(sex==='M'){
            tmb = 88.362 + (13.397 * usedWeight) + (4.799 * height) - (5.677 * age);
        } else {
            tmb = 447.593 + (9.247 * usedWeight) + (3.098 * height) - (4.330 * age);
        }
        tmb = Math.round(tmb);
        this.modal.querySelector('#resultTmb').innerText = 'TMB: ' + tmb + ' kcal';

        // Use activity multiplier from data attribute (fallback 1.2)
        const factor = activity_multiplier || 1.2;
        let calories = Math.round(tmb * factor);
        // apply goal caloric adjustment (from DB meta)
        calories = calories + (goal_caloric_adjustment || 0);
        this.modal.querySelector('#resultCalories').innerText = calories + ' kcal';

        // Macronutrients: use goal percentages if provided, else default 30/50/20
        const proteinsPerc = goal_protein || 30;
        const carbsPerc = goal_carbs || 50;
        const fatsPerc = goal_fat || 20;
        const proteins = Math.round((proteinsPerc/100 * calories)/4);
        const carbs = Math.round((carbsPerc/100 * calories)/4);
        const fats = Math.round((fatsPerc/100 * calories)/9);
        this.modal.querySelector('#resultProteins').innerText = proteins + ' g';
        this.modal.querySelector('#resultCarbs').innerText = carbs + ' g';
        this.modal.querySelector('#resultFats').innerText = fats + ' g';

        // Update percentages display
        const proteinPercEl = this.modal.querySelector('#resultProteinPercent');
        const carbsPercEl = this.modal.querySelector('#resultCarbsPercent');
        const fatsPercEl = this.modal.querySelector('#resultFatsPercent');
        if(proteinPercEl) proteinPercEl.innerText = proteinsPerc + '%';
        if(carbsPercEl) carbsPercEl.innerText = carbsPerc + '%';
        if(fatsPercEl) fatsPercEl.innerText = fatsPerc + '%';

        // Update progress bars
        const proteinBar = this.modal.querySelector('#proteinBar');
        const carbsBar = this.modal.querySelector('#carbsBar');
        const fatsBar = this.modal.querySelector('#fatsBar');
        if(proteinBar) proteinBar.style.width = proteinsPerc + '%';
        if(carbsBar) carbsBar.style.width = carbsPerc + '%';
        if(fatsBar) fatsBar.style.width = fatsPerc + '%';

        // Update donut chart (SVG)
        const circumference = 2 * Math.PI * 40; // radius = 40
        const proteinDash = (proteinsPerc / 100) * circumference;
        const carbsDash = (carbsPerc / 100) * circumference;
        const fatsDash = (fatsPerc / 100) * circumference;

        const donutProtein = this.modal.querySelector('#donutProtein');
        const donutCarbs = this.modal.querySelector('#donutCarbs');
        const donutFats = this.modal.querySelector('#donutFats');

        if(donutProtein) {
            donutProtein.setAttribute('stroke-dasharray', proteinDash + ' ' + circumference);
            donutProtein.setAttribute('stroke-dashoffset', '0');
        }
        if(donutCarbs) {
            donutCarbs.setAttribute('stroke-dasharray', carbsDash + ' ' + circumference);
            donutCarbs.setAttribute('stroke-dashoffset', -proteinDash);
        }
        if(donutFats) {
            donutFats.setAttribute('stroke-dasharray', fatsDash + ' ' + circumference);
            donutFats.setAttribute('stroke-dashoffset', -(proteinDash + carbsDash));
        }

        // show note if weight adjusted
        let noteElem = this.modal.querySelector('#nutriWeightNote');
        if(!noteElem){
            noteElem = document.createElement('div');
            noteElem.id = 'nutriWeightNote';
            noteElem.className = 'weight-adjusted-note';
            // Insert after results-header
            const resultsHeader = this.modal.querySelector('.results-header');
            if(resultsHeader && resultsHeader.nextSibling) {
                resultsHeader.parentNode.insertBefore(noteElem, resultsHeader.nextSibling);
            } else {
                this.modal.querySelector('#nutriResults').prepend(noteElem);
            }
        }

        if(usedWeightNote) {
            const noteClass = usedWeightType === 'obesity' ? 'obesity' : 'underweight';
            noteElem.className = 'weight-adjusted-note ' + noteClass;
            noteElem.innerHTML = '<div class="weight-note-icon"><i class="fas fa-calculator"></i></div>' +
                '<div class="weight-note-content">' +
                '<strong>Cálculo Ajustado</strong>' +
                '<span>' + usedWeightNote + '</span>' +
                '</div>';
            noteElem.style.display = 'flex';
        } else {
            noteElem.style.display = 'none';
            noteElem.innerHTML = '';
        }

        // store results for save
        this.modal.dataset.nutri = JSON.stringify({
            sex, height, weight, usedWeight, activity_id, goal_id, imc: parseFloat(imcFixed), age, tmb, calories, proteins, carbs, fats
        });
    };
    NutritionWizard.prototype.init = function(){
        const self = this;
        // click options
        this.modal.querySelectorAll('.nutri-option').forEach(opt=>{
            // click selects
            opt.addEventListener('click', function(){
                const parent = this.parentElement;
                parent.querySelectorAll('.nutri-option').forEach(o=>{ o.classList.remove('selected'); o.setAttribute('aria-pressed','false'); });
                this.classList.add('selected');
                this.setAttribute('aria-pressed','true');
                // update results live when selection changes on steps 2/3
                if(self.current === self.steps.length-1){ self.calculateResults(); }
            });
            // keyboard support: Enter or Space activates
            opt.addEventListener('keydown', function(e){
                if(e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar'){
                    e.preventDefault();
                    this.click();
                }
                // allow arrow keys to move focus between options
                if(e.key === 'ArrowDown' || e.key === 'ArrowRight'){
                    e.preventDefault();
                    const next = this.nextElementSibling || this.parentElement.querySelector('.nutri-option');
                    if(next) next.focus();
                }
                if(e.key === 'ArrowUp' || e.key === 'ArrowLeft'){
                    e.preventDefault();
                    const prev = this.previousElementSibling || this.parentElement.querySelector('.nutri-option:last-child');
                    if(prev) prev.focus();
                }
            });
            // ensure role/aria on init
            if(!opt.hasAttribute('tabindex')) opt.setAttribute('tabindex','0');
            if(!opt.hasAttribute('role')) opt.setAttribute('role','button');
            if(!opt.hasAttribute('aria-pressed')) opt.setAttribute('aria-pressed','false');
        });
        // buttons
        this.modal.querySelector('#nutriNext').addEventListener('click', function(){
            if(!self.validateStep(self.current)) return;
            if(self.current < self.steps.length-1){
                self.current++;
                if(self.current===self.steps.length-1) self.calculateResults();
                self.showStep(self.current);
            }
        });
        this.modal.querySelector('#nutriBack').addEventListener('click', function(){ if(self.current>0){ self.current--; self.showStep(self.current); } });
        this.modal.querySelector('#nutriSave').addEventListener('click', function(){
            const payload = JSON.parse(self.modal.dataset.nutri || '{}');
            payload.customer_id = parseInt(self.modal.dataset.customerId || 0,10);
            payload.sex = (self.modal.querySelector('input[name="sex"]:checked')||{}).value || null;
            if(!payload.customer_id){ window.alert('Usuário não autenticado.'); return; }
            const dirVal = document.getElementById('dir_site')?.value || '';
            const dir = dirVal?('/'+dirVal):'';
            $.post(dir + '/profile/save_nutritional_profile', payload, function(resp){
                try{ var r = JSON.parse(resp); } catch(e){ window.alert('Erro de servidor'); return; }
                if(r.success){ self.modal.style.display = 'none'; location.reload(); } else { window.alert(r.message || 'Erro ao salvar perfil'); }
            }).fail(function(){ window.alert('Erro na requisição'); });
        });
        // init values from dataset
        this.modal.dataset.clientBirth = this.modal.dataset.clientBirth || '';
        this.modal.dataset.customerId = this.modal.dataset.customerId || '';
        this.showStep(0);

        // ensure dots and step labels are clickable (stepper navigation)
        Array.from(this.modal.querySelectorAll('.step-dot')).forEach((sd, idx)=>{
            sd.addEventListener('click', function(){
                // don't advance to a later step unless current is valid
                if(idx > self.current){
                    if(!self.validateStep(self.current)) return; // block advancing
                    // allow advance
                    self.current = idx;
                    self.showStep(self.current);
                } else if(idx < self.current) {
                    // going back: confirm optional
                    if(confirm('Deseja voltar para este passo?')){ self.current = idx; self.showStep(self.current); }
                }
                // update aria-selected on step dots
                Array.from(self.modal.querySelectorAll('.step-dot')).forEach((el,i)=> el.setAttribute('aria-selected', i===self.current?'true':'false'));
            });
            sd.setAttribute('tabindex','0');
            sd.setAttribute('role','button');
            sd.addEventListener('keydown', function(e){ if(e.key==='Enter' || e.key===' '){ e.preventDefault(); sd.click(); } });
        });
    };

    // export
    window.NutritionWizard = NutritionWizard;

})(window, jQuery);

