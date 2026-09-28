import { createIcons, icons } from 'lucide';

// Initialize Lucide Icons on DOM and after dynamic changes
window.refreshIcons = () => {
    createIcons({ icons });
};

// Scroll Reveal & Stagger Animation Observer
window.initScrollAnimations = () => {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll('.reveal, [data-reveal], .reveal-fade-in, .reveal-fade-left, .reveal-fade-right, .reveal-scale-in').forEach(el => {
            el.classList.add('is-revealed');
        });
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-revealed');
                obs.unobserve(entry.target);
            }
        });
    }, {
        root: null,
        rootMargin: '0px 0px -40px 0px',
        threshold: 0.1
    });

    document.querySelectorAll('.reveal, [data-reveal], .reveal-fade-in, .reveal-fade-left, .reveal-fade-right, .reveal-scale-in').forEach(el => {
        observer.observe(el);
    });
};

// Animated Impact Number Counters (e.g. 10,000+, 500+, 4)
window.initCounters = () => {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const counterObserver = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const rawTarget = el.getAttribute('data-counter');
                if (!rawTarget) return;

                const match = rawTarget.match(/^([\d,\.]+)(.*)$/);
                if (!match) return;

                const targetNum = parseFloat(match[1].replace(/,/g, ''));
                const suffix = match[2] || '';
                const isCommaFormatted = match[1].includes(',');
                const duration = 1600; // ms
                const start = performance.now();

                const updateCount = (currentTime) => {
                    const elapsed = currentTime - start;
                    const progress = Math.min(elapsed / duration, 1);
                    // Easing out cubic
                    const easeOut = 1 - Math.pow(1 - progress, 3);
                    const current = Math.floor(easeOut * targetNum);

                    const formatted = isCommaFormatted ? current.toLocaleString('en-IN') : current;
                    el.textContent = formatted + suffix;

                    if (progress < 1) {
                        requestAnimationFrame(updateCount);
                    } else {
                        const finalFormatted = isCommaFormatted ? targetNum.toLocaleString('en-IN') : targetNum;
                        el.textContent = finalFormatted + suffix;
                    }
                };

                requestAnimationFrame(updateCount);
                obs.unobserve(el);
            }
        });
    }, {
        threshold: 0.2
    });

    document.querySelectorAll('[data-counter]').forEach(el => {
        counterObserver.observe(el);
    });
};

document.addEventListener('DOMContentLoaded', () => {
    window.refreshIcons();
    window.initScrollAnimations();
    window.initCounters();
});

document.addEventListener('livewire:navigated', () => {
    window.refreshIcons();
    window.initScrollAnimations();
    window.initCounters();
});

// Alpine global data / stores for prototype modals & toast notices
document.addEventListener('alpine:init', () => {
    if (window.Alpine) {
        window.Alpine.store('ngoApp', {
            donateModalOpen: false,
            volunteerModalOpen: false,
            loginNoticeOpen: false,
            lightboxImage: null,
            lightboxTitle: '',
            selectedDonationAmount: 1000,
            donationCustomAmount: '',
            volunteerType: 'volunteer',
            
            openDonate(amount = 1000) {
                this.selectedDonationAmount = amount;
                this.donateModalOpen = true;
                setTimeout(() => window.refreshIcons(), 50);
            },
            closeDonate() {
                this.donateModalOpen = false;
            },
            openVolunteer(type = 'volunteer') {
                this.volunteerType = type;
                this.volunteerModalOpen = true;
                setTimeout(() => window.refreshIcons(), 50);
            },
            closeVolunteer() {
                this.volunteerModalOpen = false;
            },
            openLoginNotice() {
                this.loginNoticeOpen = true;
                setTimeout(() => window.refreshIcons(), 50);
            },
            closeLoginNotice() {
                this.loginNoticeOpen = false;
            },
            openLightbox(img, title) {
                this.lightboxImage = img;
                this.lightboxTitle = title;
                setTimeout(() => window.refreshIcons(), 50);
            },
            closeLightbox() {
                this.lightboxImage = null;
                this.lightboxTitle = '';
            }
        });
    }
});
