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