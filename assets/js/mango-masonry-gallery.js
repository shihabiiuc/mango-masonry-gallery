(function () {
	'use strict';

	var l10n = window.mangoMasonryGalleryL10n || {
		viewer: 'Image viewer',
		close: 'Close lightbox',
		prev: 'Previous image',
		next: 'Next image',
		imageAlt: 'Gallery image %d'
	};

	var current = 0;
	var btns = [];

	function isEditMode() {
		return document.body.classList.contains('elementor-editor-active') ||
			!!(window.elementorFrontend && window.elementorFrontend.isEditMode && window.elementorFrontend.isEditMode());
	}

	function el(tag, attrs, html) {
		var node = document.createElement(tag);
		Object.keys(attrs).forEach(function (key) { node.setAttribute(key, attrs[key]); });
		if (html) node.innerHTML = html;
		return node;
	}

	function ensureLightbox() {
		var lb = document.getElementById('mmg-lightbox');
		if (lb) return lb;

		lb = el('div', { id: 'mmg-lightbox', role: 'dialog', 'aria-modal': 'true', 'aria-label': l10n.viewer, 'aria-hidden': 'true' });
		lb.appendChild(el('button', { type: 'button', id: 'mmg-lightbox-close', 'aria-label': l10n.close }, '&#10005;'));
		lb.appendChild(el('button', { type: 'button', id: 'mmg-lightbox-prev', 'aria-label': l10n.prev }, '&#8249;'));
		lb.appendChild(el('button', { type: 'button', id: 'mmg-lightbox-next', 'aria-label': l10n.next }, '&#8250;'));
		var stage = el('div', { id: 'mmg-lightbox-stage' });
		stage.appendChild(el('img', { id: 'mmg-lightbox-img', src: '', alt: '' }));
		lb.appendChild(stage);
		lb.appendChild(el('div', { id: 'mmg-lightbox-counter' }));
		document.body.appendChild(lb);

		lb.addEventListener('click', function (e) {
			var button = e.target.closest('button');
			var id = button ? button.id : '';
			if (id === 'mmg-lightbox-close' || e.target === lb) { closeLightbox(); return; }
			if (id === 'mmg-lightbox-prev') { show(current - 1); return; }
			if (id === 'mmg-lightbox-next') { show(current + 1); }
		});

		return lb;
	}

	function show(index) {
		current = (index + btns.length) % btns.length;
		var btn = btns[current];
		var img = document.getElementById('mmg-lightbox-img');
		img.src = btn.dataset.src;
		img.alt = btn.dataset.alt || l10n.imageAlt.replace('%d', current + 1);
		document.getElementById('mmg-lightbox-counter').textContent = (current + 1) + ' / ' + btns.length;
	}

	function openLightbox(btn) {
		var grid = btn.closest('.mmg-grid');
		var lb = ensureLightbox();
		var styles = getComputedStyle(grid);

		btns = Array.prototype.slice.call(grid.querySelectorAll('.mmg-grid__btn'));
		lb.style.setProperty('--mmg-lightbox-bg-active', styles.getPropertyValue('--mmg-lightbox-bg').trim() || 'rgba(10,7,5,0.96)');
		lb.style.setProperty('--mmg-accent-active', styles.getPropertyValue('--mmg-accent').trim() || '#d4b5a5');
		show(btns.indexOf(btn));

		lb.classList.add('mmg-lightbox--open');
		lb.setAttribute('aria-hidden', 'false');
		document.body.style.overflow = 'hidden';

		requestAnimationFrame(function () {
			document.getElementById('mmg-lightbox-close').focus();
		});
	}

	function closeLightbox() {
		var lb = document.getElementById('mmg-lightbox');
		if (!lb || !lb.classList.contains('mmg-lightbox--open')) return;
		lb.classList.remove('mmg-lightbox--open');
		lb.setAttribute('aria-hidden', 'true');
		document.body.style.overflow = '';
		if (btns[current]) btns[current].focus();
	}

	// Delegated listeners: they also cover galleries rendered after page load
	// (e.g. widgets re-rendered by the Elementor editor).
	document.addEventListener('click', function (e) {
		var btn = e.target.closest && e.target.closest('.mmg-grid__btn');
		if (!btn || isEditMode()) return;
		openLightbox(btn);
	});

	document.addEventListener('keydown', function (e) {
		var lb = document.getElementById('mmg-lightbox');
		if (lb && lb.classList.contains('mmg-lightbox--open')) {
			if (e.key === 'Escape') { e.preventDefault(); closeLightbox(); }
			if (e.key === 'ArrowLeft') { e.preventDefault(); show(current - 1); }
			if (e.key === 'ArrowRight') { e.preventDefault(); show(current + 1); }
			return;
		}

		var btn = e.target.closest && e.target.closest('.mmg-grid__btn');
		if (btn && !isEditMode() && (e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar')) {
			e.preventDefault();
			openLightbox(btn);
		}
	});
})();
