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
document.addEventListener("DOMContentLoaded", function(){

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