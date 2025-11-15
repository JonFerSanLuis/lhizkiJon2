        
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn-custom');
            const slider = document.querySelector('.button-slider');
            const loginSection = document.getElementById('login-section');
            const registerSection = document.getElementById('register-section');

            console.log('loginsection encontrado:', loginSection); // debug

            buttons.forEach((button, index) => {
                button.addEventListener('click', function() {
                    // remover clase activa de todos los botones
                    buttons.forEach(btn => btn.classList.remove('active'));
                    // agregar clase activa al boton clickeado
                    button.classList.add('active');
                      if (index === 0) {
                        // boton sartu (login)
                        console.log('mostrando seccion login'); // debug
                        
                        // mostrar login
                        loginSection.style.opacity = '1';
                        loginSection.style.transform = 'translateX(-50%) translateY(0)';
                        loginSection.style.pointerEvents = 'auto';
                        
                        // ocultar register section
                        registerSection.style.opacity = '0';
                        registerSection.style.transform = 'translateX(-50%) translateY(-20px)';
                        registerSection.style.pointerEvents = 'none';
                        
                        slider.style.transform = 'translateX(0)';
                    } else {
                        // boton erregistratu (registro)  
                        console.log('mostrando seccion registro'); // debug
                        
                        // ocultar login
                        loginSection.style.opacity = '0';
                        loginSection.style.transform = 'translateX(-50%) translateY(-20px)';
                        loginSection.style.pointerEvents = 'none';
                        
                        // mostrar register section
                        registerSection.style.opacity = '1';
                        registerSection.style.transform = 'translateX(-50%) translateY(0)';
                        registerSection.style.pointerEvents = 'auto';
                        
                        slider.style.transform = 'translateX(calc(100% + 0.2rem))';
                    }
                });
            });
            
            // establecer posicion inicial al primer boton
            slider.style.transform = 'translateX(0)';
            buttons[0].classList.add('active');
        });