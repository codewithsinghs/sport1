const ZOOM_LEVELS = [1, 1.8, 2.8, 4];

class Lightbox {
    constructor(root) {
        this.root = root;
        this.triggers = Array.from(root.querySelectorAll('[data-lightbox-trigger]'));
        this.dialog = root.querySelector('[data-lightbox-dialog]');
        if (!this.dialog || this.triggers.length === 0) return;

        this.image = this.dialog.querySelector('[data-lightbox-image]');
        this.counter = this.dialog.querySelector('[data-lightbox-counter]');
        this.caption = this.dialog.querySelector('[data-lightbox-caption]');
        this.credit = this.dialog.querySelector('[data-lightbox-credit]');
        this.stage = this.dialog.querySelector('[data-lightbox-stage]');
        this.strip = this.dialog.querySelector('[data-lightbox-strip]');
        this.thumbs = this.strip ? Array.from(this.strip.children) : [];

        this.index = 0;
        this.zoom = 0;
        this.lastFocus = null;
        this.drag = null;

        this.thumbs.forEach((thumb, i) => {
            thumb.addEventListener('click', () => this.show(i));
        });

        this.triggers.forEach((trigger, i) => {
            trigger.addEventListener('click', (event) => {
                event.preventDefault();
                this.open(i);
            });
        });

        this.dialog.querySelector('[data-lightbox-close]')?.addEventListener('click', () => this.close());
        this.dialog.querySelector('[data-lightbox-prev]')?.addEventListener('click', () => this.step(-1));
        this.dialog.querySelector('[data-lightbox-next]')?.addEventListener('click', () => this.step(1));
        this.dialog.querySelectorAll('[data-lightbox-backdrop]').forEach((zone) => {
            zone.addEventListener('click', () => this.close());
        });

        this.image.addEventListener('click', () => this.setZoom(this.zoom + 1));

        this.image.addEventListener('wheel', (event) => {
            event.preventDefault();
            this.setZoom(this.zoom + (event.deltaY < 0 ? 1 : -1));
        }, { passive: false });

        this.stage.addEventListener('pointerdown', (event) => {
            if (this.zoom === 0) return;
            this.drag = { x: event.clientX, y: event.clientY, ox: this.offsetX, oy: this.offsetY };
            this.stage.setPointerCapture(event.pointerId);
        });

        this.stage.addEventListener('pointermove', (event) => {
            if (!this.drag) return;
            this.offsetX = this.drag.ox + (event.clientX - this.drag.x);
            this.offsetY = this.drag.oy + (event.clientY - this.drag.y);
            this.paintTransform();
        });

        ['pointerup', 'pointercancel', 'pointerleave'].forEach((type) => {
            this.stage.addEventListener(type, () => { this.drag = null; });
        });

        let touch = null;
        this.stage.addEventListener('touchstart', (event) => {
            if (event.touches.length !== 1 || this.zoom > 0) return;
            touch = { x: event.touches[0].clientX, y: event.touches[0].clientY };
        }, { passive: true });

        this.stage.addEventListener('touchend', (event) => {
            if (!touch || this.zoom > 0) return;
            const dx = event.changedTouches[0].clientX - touch.x;
            const dy = event.changedTouches[0].clientY - touch.y;
            if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy)) this.step(dx < 0 ? 1 : -1);
            touch = null;
        });

        document.addEventListener('keydown', (event) => {
            if (!this.dialog.open) return;

            switch (event.key) {
                case 'Escape':
                    this.close();
                    break;
                case 'ArrowRight':
                    this.step(1);
                    break;
                case 'ArrowLeft':
                    this.step(-1);
                    break;
                case 'Home':
                    event.preventDefault();
                    this.show(0);
                    break;
                case 'End':
                    event.preventDefault();
                    this.show(this.triggers.length - 1);
                    break;
                case '+':
                case '=':
                    this.setZoom(this.zoom + 1);
                    break;
                case '-':
                case '_':
                    this.setZoom(this.zoom - 1);
                    break;
                case '0':
                    this.setZoom(0);
                    break;
                case 'Tab':
                    this.trapFocus(event);
                    break;
            }
        });
    }

    open(index) {
        this.lastFocus = document.activeElement;
        this.offsetX = 0;
        this.offsetY = 0;
        this.dialog.showModal();
        document.documentElement.classList.add('lb-open');
        this.show(index);
        this.dialog.querySelector('[data-lightbox-close]')?.focus();
    }

    close() {
        this.dialog.close();
        document.documentElement.classList.remove('lb-open');
        this.setZoom(0);
        this.lastFocus?.focus();
    }

    step(delta) {
        const next = (this.index + delta + this.triggers.length) % this.triggers.length;
        this.show(next);
    }

    show(index) {
        this.index = index;
        this.setZoom(0);

        const source = this.triggers[index].dataset;
        this.image.src = source.full;
        this.image.alt = source.alt ?? '';
        this.image.width = Number(source.width) || 0;
        this.image.height = Number(source.height) || 0;

        if (this.counter) {
            this.counter.textContent = `${index + 1} / ${this.triggers.length}`;
        }

        if (this.caption) {
            this.caption.textContent = source.caption ?? '';
            this.caption.hidden = !source.caption;
        }

        if (this.credit) {
            this.credit.textContent = source.credit ? `© ${source.credit}` : '';
            this.credit.hidden = !source.credit;
        }

        this.thumbs.forEach((thumb, i) => {
            thumb.classList.toggle('is-active', i === index);
            thumb.setAttribute('aria-current', i === index ? 'true' : 'false');
            if (i === index) thumb.scrollIntoView({ block: 'nearest', inline: 'center', behavior: 'smooth' });
        });

        [1, -1].forEach((delta) => {
            const neighbour = this.triggers[(index + delta + this.triggers.length) % this.triggers.length];
            if (!neighbour?.dataset.full) return;
            const preloaded = new Image();
            preloaded.src = neighbour.dataset.full;
        });
    }

    setZoom(level) {
        this.zoom = Math.max(0, Math.min(ZOOM_LEVELS.length - 1, level));
        if (this.zoom === 0) {
            this.offsetX = 0;
            this.offsetY = 0;
        }
        this.paintTransform();
    }

    paintTransform() {
        const scale = ZOOM_LEVELS[this.zoom];
        this.image.style.transform = `translate3d(${this.offsetX}px, ${this.offsetY}px, 0) scale(${scale})`;
        this.image.classList.toggle('is-zoomed', scale > 1);
        this.stage.classList.toggle('is-zoomed', scale > 1);
    }

    trapFocus(event) {
        const focusable = Array.from(this.dialog.querySelectorAll('button:not([disabled]), [href], [tabindex]:not([tabindex="-1"])'))
            .filter((el) => el.offsetParent !== null);
        if (focusable.length === 0) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    }
}

function boot() {
    document.querySelectorAll('[data-lightbox]').forEach((root) => {
        if (root.dataset.lightboxBooted === '1') return;
        root.dataset.lightboxBooted = '1';
        new Lightbox(root);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}

export { Lightbox, boot as bootLightboxes };
