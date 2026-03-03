/* ===========================
   AcaHub — Interactivity
   =========================== */

document.addEventListener('DOMContentLoaded', () => {

    // ---------- Mobile Menu Toggle ----------
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');
    const navActions = document.querySelector('.nav-actions');

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
            if (navActions) navActions.classList.toggle('active');
        });

        // Close menu when clicking a nav link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
                if (navActions) navActions.classList.remove('active');
            });
        });
    }

    // ---------- Navbar Scroll Effect ----------
    const navbar = document.getElementById('navbar');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // ---------- Scroll Animations (Intersection Observer) ----------
    const fadeElements = document.querySelectorAll('.fade-in');

    const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px 0px -40px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    fadeElements.forEach(el => observer.observe(el));

    // ---------- Stat Counter Animation ----------
    const statNumbers = document.querySelectorAll('.stat-number');

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.getAttribute('data-target'));
                animateCounter(entry.target, target);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    statNumbers.forEach(el => counterObserver.observe(el));

    function animateCounter(element, target) {
        const duration = 2000;
        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            // Ease out cubic
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(easeOut * target);

            element.textContent = current.toLocaleString();

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }

        requestAnimationFrame(update);
    }

    // ---------- Progress Bar Animation ----------
    const barFills = document.querySelectorAll('.bar-fill');

    const barObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const targetWidth = entry.target.style.width;
                entry.target.style.width = '0%';
                setTimeout(() => {
                    entry.target.style.width = targetWidth;
                }, 200);
                barObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    barFills.forEach(el => barObserver.observe(el));

    // ---------- Smooth Scroll for Anchor Links ----------
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const target = document.querySelector(targetId);
            if (target) {
                const navHeight = navbar.offsetHeight;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navHeight;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
});
/* ===========================
   AcaHub — Premium Login Effects
   =========================== */

document.addEventListener("DOMContentLoaded", () => {

    const loginPage = document.querySelector(".acahub-auth-page");
    const loginCard = document.querySelector(".acahub-auth-box");
    const mouseLight = document.querySelector(".mouse-light");
    const loginButton = document.querySelector(".acahub-btn");
    const inputs = document.querySelectorAll(".acahub-input input");

    /* ---------- 3D Tilt Effect ---------- */
    if (loginPage && loginCard) {
        loginPage.addEventListener("mousemove", (e) => {
            const rect = loginCard.getBoundingClientRect();
            const cardX = rect.left + rect.width / 2;
            const cardY = rect.top + rect.height / 2;

            const rotateX = -(e.clientY - cardY) / 30;
            const rotateY = (e.clientX - cardX) / 30;

            loginCard.style.transform =
                `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });

        loginPage.addEventListener("mouseleave", () => {
            loginCard.style.transform = "rotateX(0deg) rotateY(0deg)";
        });
    }

    /* ---------- Mouse Spotlight ---------- */
    if (mouseLight && loginPage) {
        loginPage.addEventListener("mousemove", (e) => {
            mouseLight.style.left = e.clientX + "px";
            mouseLight.style.top = e.clientY + "px";
        });
    }

    /* ---------- Input Glow Effect ---------- */
    inputs.forEach(input => {
        input.addEventListener("focus", () => {
            input.style.boxShadow = "0 0 0 4px rgba(232,122,46,0.2)";
        });

        input.addEventListener("blur", () => {
            input.style.boxShadow = "none";
        });
    });

    /* ---------- Button Ripple Effect ---------- */
    if (loginButton) {
        loginButton.addEventListener("click", function (e) {
            const circle = document.createElement("span");
            const diameter = Math.max(this.clientWidth, this.clientHeight);
            const radius = diameter / 2;

            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${e.clientX - this.getBoundingClientRect().left - radius}px`;
            circle.style.top = `${e.clientY - this.getBoundingClientRect().top - radius}px`;
            circle.classList.add("ripple");

            const ripple = this.getElementsByClassName("ripple")[0];
            if (ripple) ripple.remove();

            this.appendChild(circle);
        });
    }

});

/* ===========================
   FORM VALIDATION & NOTIFICATIONS
   =========================== */

document.addEventListener("DOMContentLoaded", function(){

    // ---------- Registration Form Validation ----------
    const registerBtn = document.getElementById("registerBtn");
    const nameInput = document.querySelector('input[placeholder="Nama Lengkap"]');
    const emailInput = document.querySelector('input[type="email"]');
    const passwordInput = document.querySelector('input[type="password"]');

    // Create notification container if it doesn't exist
    function createNotificationContainer() {
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                width: 300px;
            `;
            document.body.appendChild(container);
        }
        return container;
    }

    // Show notification function
    function showNotification(message, type = 'error') {
        const container = createNotificationContainer();
        
        const notification = document.createElement('div');
        notification.style.cssText = `
            background: ${type === 'error' ? '#ff4757' : type === 'success' ? '#2ed573' : '#ffa726'};
            color: white;
            padding: 12px 16px;
            margin-bottom: 10px;
            border-radius: 8px;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease;
            position: relative;
            padding-right: 40px;
        `;
        
        notification.innerHTML = `
            ${message}
            <button style="
                position: absolute; 
                right: 8px; 
                top: 50%; 
                transform: translateY(-50%);
                background: none; 
                border: none; 
                color: white; 
                font-size: 16px; 
                cursor: pointer;
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
            " onclick="this.parentElement.remove()">×</button>
        `;

        container.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        }, 10);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.transform = 'translateX(100%)';
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }

    // Validation functions
    function validateName(name) {
        if (!name.trim()) {
            return { valid: false, message: 'Nama lengkap harus diisi' };
        }
        if (name.trim().length < 2) {
            return { valid: false, message: 'Nama minimal 2 karakter' };
        }
        if (!/^[a-zA-Z\s]+$/.test(name)) {
            return { valid: false, message: 'Nama hanya boleh berisi huruf dan spasi' };
        }
        return { valid: true, message: 'Nama valid' };
    }

    function validateEmail(email) {
        if (!email.trim()) {
            return { valid: false, message: 'Email harus diisi' };
        }
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            return { valid: false, message: 'Format email tidak valid' };
        }
        return { valid: true, message: 'Email valid' };
    }

    function validatePassword(password) {
        if (!password) {
            return { valid: false, message: 'Password harus diisi' };
        }
        if (password.length < 8) {
            return { valid: false, message: 'Password minimal 8 karakter' };
        }
        if (!/(?=.*[a-z])/.test(password)) {
            return { valid: false, message: 'Password harus mengandung huruf kecil' };
        }
        if (!/(?=.*[A-Z])/.test(password)) {
            return { valid: false, message: 'Password harus mengandung huruf besar' };
        }
        if (!/(?=.*\d)/.test(password)) {
            return { valid: false, message: 'Password harus mengandung angka' };
        }
        return { valid: true, message: 'Password kuat' };
    }

    // Real-time validation for each input
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            const validation = validateName(this.value);
            const parentDiv = this.parentElement;
            
            // Remove all validation classes
            parentDiv.classList.remove('valid', 'invalid', 'warning');
            this.style.borderColor = '';
            
            if (!validation.valid && this.value.length > 0) {
                showNotification(validation.message, 'error');
                parentDiv.classList.add('invalid');
            } else if (validation.valid) {
                parentDiv.classList.add('valid');
            }
        });

        nameInput.addEventListener('blur', function() {
            if (this.value.length > 0) {
                const validation = validateName(this.value);
                const parentDiv = this.parentElement;
                
                parentDiv.classList.remove('valid', 'invalid', 'warning');
                
                if (!validation.valid) {
                    parentDiv.classList.add('invalid');
                } else {
                    parentDiv.classList.add('valid');
                }
            }
        });
    }

    if (emailInput) {
        emailInput.addEventListener('input', function() {
            const validation = validateEmail(this.value);
            const parentDiv = this.parentElement;
            
            // Remove all validation classes
            parentDiv.classList.remove('valid', 'invalid', 'warning');
            this.style.borderColor = '';
            
            if (!validation.valid && this.value.length > 0) {
                showNotification(validation.message, 'error');
                parentDiv.classList.add('invalid');
            } else if (validation.valid) {
                showNotification(validation.message, 'success');
                parentDiv.classList.add('valid');
            }
        });

        emailInput.addEventListener('blur', function() {
            if (this.value.length > 0) {
                const validation = validateEmail(this.value);
                const parentDiv = this.parentElement;
                
                parentDiv.classList.remove('valid', 'invalid', 'warning');
                
                if (!validation.valid) {
                    parentDiv.classList.add('invalid');
                } else {
                    parentDiv.classList.add('valid');
                }
            }
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const validation = validatePassword(this.value);
            const parentDiv = this.parentElement;
            
            // Remove all validation classes
            parentDiv.classList.remove('valid', 'invalid', 'warning');
            this.style.borderColor = '';
            
            if (!validation.valid && this.value.length > 0) {
                showNotification(validation.message, 'warning');
                parentDiv.classList.add('warning');
            } else if (validation.valid) {
                showNotification(validation.message, 'success');
                parentDiv.classList.add('valid');
            }
        });

        passwordInput.addEventListener('blur', function() {
            if (this.value.length > 0) {
                const validation = validatePassword(this.value);
                const parentDiv = this.parentElement;
                
                parentDiv.classList.remove('valid', 'invalid', 'warning');
                
                if (!validation.valid) {
                    parentDiv.classList.add('warning');
                } else {
                    parentDiv.classList.add('valid');
                }
            }
        });
    }

    // Register button validation
    if (registerBtn) {
        registerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Prevent multiple clicks during processing
            if (this.classList.contains('loading')) return;
            
            const nameVal = nameInput ? nameInput.value : '';
            const emailVal = emailInput ? emailInput.value : '';
            const passwordVal = passwordInput ? passwordInput.value : '';

            const nameValidation = validateName(nameVal);
            const emailValidation = validateEmail(emailVal);
            const passwordValidation = validatePassword(passwordVal);

            // Show all validation errors
            let hasErrors = false;
            if (!nameValidation.valid) {
                showNotification(nameValidation.message, 'error');
                nameInput.parentElement.classList.add('invalid');
                hasErrors = true;
            }
            if (!emailValidation.valid) {
                showNotification(emailValidation.message, 'error');
                emailInput.parentElement.classList.add('invalid');
                hasErrors = true;
            }
            if (!passwordValidation.valid) {
                showNotification(passwordValidation.message, 'error');
                passwordInput.parentElement.classList.add('warning');
                hasErrors = true;
            }

            // If all validations pass
            if (nameValidation.valid && emailValidation.valid && passwordValidation.valid) {
                // Add loading state
                this.classList.add('loading');
                this.textContent = 'Mendaftarkan...';
                
                showNotification('Memproses registrasi...', 'success');
                
                // Simulate registration process
                setTimeout(() => {
                    showNotification('Registrasi berhasil! Selamat datang di AcaHub', 'success');
                    
                    setTimeout(() => {
                        showNotification('Mengarahkan ke halaman login...', 'success');
                        
                        setTimeout(() => {
                            window.location.href = 'login.html';
                        }, 1000);
                    }, 1500);
                }, 2000);
            } else {
                showNotification('Mohon perbaiki data yang tidak valid', 'error');
            }
        });
    }

const light = document.getElementById("mouseLight");
const button = document.getElementById("loginBtn");

/* Mouse glow effect */
document.addEventListener("mousemove", function(e){

    if(light){
        light.style.left = e.clientX + "px";
        light.style.top = e.clientY + "px";
    }

});

/* Klik tombol login */
if(button){

    button.addEventListener("click", function(e){

        const email = document.querySelector('input[type="email"]').value;
        const password = document.querySelector('input[type="password"]').value;

        if(!email || !password){
            alert("Isi email dan password dulu");
            return;
        }

        /* Ripple effect */
        const ripple = document.createElement("span");
        ripple.classList.add("ripple");

        const rect = button.getBoundingClientRect();

        ripple.style.left = (e.clientX - rect.left) + "px";
        ripple.style.top = (e.clientY - rect.top) + "px";

        button.appendChild(ripple);

        /* Redirect ke dashboard */
        setTimeout(function(){
            window.location.replace("index.html");
        }, 400);

    });

}

});
