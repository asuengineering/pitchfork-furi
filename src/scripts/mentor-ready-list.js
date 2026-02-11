/**
 * mentor-ready-list.js
 *
 * Filtering update: allow BOTH selects to be active simultaneously (AND logic).
 * Keeps Reset button and mentor count UI.
 */

function mentorReadyInit() {
	const programSelect = document.getElementById('mentor-filter-program');
	const projectSelect = document.getElementById('mentor-filter-project');
	const resetButton = document.getElementById('filter-reset');
	const countEl = document.querySelector('.ready-mentor-count');
	const list = document.querySelector('.uds-profile-grid');

	// Bail early if block isn't present
	if (!programSelect || !projectSelect || !list) {
		return;
	}

	const items = Array.from(list.querySelectorAll('.mentor-card'));

	// Normalize CSV data-attributes into lowercase tokens
	function tokensFromAttr(str) {
		if (!str) return [];
		return String(str)
			.split(',')
			.map(s => s.trim().toLowerCase())
			.filter(Boolean);
	}

	// Determine whether a mentor card matches a given filter token
	function itemMatches(item, token, attrName) {
		if (!token) return true; // empty filter -> match all
		const tokens = tokensFromAttr(item.getAttribute(attrName));
		return tokens.includes(token.toLowerCase());
	}

	// Update the visible count text
	function updateCountText(visible, total) {
		if (!countEl) return;
		if (visible === total) {
			countEl.textContent = 'Displaying all mentors.';
		} else if (visible === 0) {
			countEl.textContent = 'No mentors match that filter.';
		} else {
			countEl.textContent = 'Showing ' + visible + ' of ' + total + ' mentors.';
		}
	}

	// Apply visibility rules to mentor cards
	// NEW: Both filters can be active and are applied together (AND logic)
	function applyFilters() {
		const programVal = (programSelect.value || '').toLowerCase();
		const projectVal = (projectSelect.value || '').toLowerCase();

		let visibleCount = 0;

		items.forEach(item => {
			const matchesProgram = itemMatches(item, programVal, 'data-programs');
			const matchesProject = itemMatches(item, projectVal, 'data-projects');

			// AND logic: if a select has a value it must match; empty = no constraint
			const shouldShow =
				(programVal ? matchesProgram : true) &&
				(projectVal ? matchesProject : true);

			if (shouldShow) {
				item.classList.remove('d-none');
				item.removeAttribute('aria-hidden');
				visibleCount++;
			} else {
				item.classList.add('d-none');
				item.setAttribute('aria-hidden', 'true');
			}
		});

		updateCountText(visibleCount, items.length);

		// Emit event others can listen to if desired
		list.dispatchEvent(
			new CustomEvent('mentorFilters.changed', {
				detail: {
					program: programVal,
					project: projectVal,
					visible: visibleCount,
					total: items.length
				}
			})
		);
	}

	// Clear filters programmatically
	function clearFilters() {
		programSelect.value = '';
		projectSelect.value = '';
		applyFilters();
		// accessibility: return focus to first control
		programSelect.focus();
	}

	// Bind change events
	function bindEvents() {
		programSelect.addEventListener('change', function () {
			applyFilters();
		});

		projectSelect.addEventListener('change', function () {
			applyFilters();
		});

		// Reset button: prevents form default reset (if inside a <form>) and does our clear
		if (resetButton) {
			resetButton.addEventListener('click', function (e) {
				e.preventDefault();
				clearFilters();
			});
		}

		// Optional: if someone else dispatches mentorFilters.clear
		document.addEventListener('mentorFilters.clear', function () {
			clearFilters();
		});
	}

	// Initialize
	bindEvents();
	applyFilters();

	// Public API (optional)
	window.mentorReadyFilters = window.mentorReadyFilters || {};
	window.mentorReadyFilters.clear = clearFilters;
}

// Vanilla JS "document ready"
document.addEventListener('DOMContentLoaded', mentorReadyInit);
