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