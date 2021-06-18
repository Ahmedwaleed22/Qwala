(function() {
	const inputs = document.querySelectorAll("input, textarea");

	inputs.forEach(input => {
		input.onclick = () => {
			input.setAttribute("data-placeholder", input.placeholder);
			input.placeholder = '';
		}
		input.onblur = () => {
			input.placeholder = input.getAttribute("data-placeholder");
			input.setAttribute("data-placeholder", "");
		}
	});
})();

function show_answer(event) {
	let target = event.target;
	let answer = target.getAttribute('data-answer');
	target.classList.add('answered');
	target.classList.remove('show-answer');
	target.innerHTML = answer;
}

(function() {
	const navIcon = document.querySelector('#nav-icon');
	const nav = document.querySelector('#nav-links');
	const navLinks = document.querySelectorAll('#nav-links li a');

	// Toggle Navbar

	navIcon.addEventListener("click", () => {
		nav.classList.toggle('nav-active');

		// NavIcon Animation
		navIcon.classList.toggle('toggle');
		// Animate Links
		navLinks.forEach((link, index) => {
			if (link.style.animation) {
				link.style.animation = '';
			} else {
				link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.4}s`;
			}
		});
	});
})();