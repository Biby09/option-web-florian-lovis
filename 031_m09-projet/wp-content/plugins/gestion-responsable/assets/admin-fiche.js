jQuery(function ($) {
	$('#post').on('submit', function (event) {
		if ($('#unitechecklist input:checked').length === 0) {
			event.preventDefault();
			alert(grFiche.messageUnite);
		}
	});
	document.getElementById("gr_add_fonction").addEventListener("click", function () {
	const container = document.getElementById("gr_fonctions_container");
	const newIndex = container.querySelectorAll("input").length + 1;

	const input = document.createElement("input");
	input.type = "text";
	input.name = "gr_fonction[]";
	input.id = "gr_fonction_" + newIndex;
	input.className = "regular-text";

	const label = document.createElement("label");
	label.htmlFor = input.id;
	label.textContent = grFiche.libelleFonction + " " + (newIndex);

	const tr = document.createElement("tr");
	const thLabel = document.createElement("th");
	thLabel.scope = "row";
	const tdInput = document.createElement("td");
	thLabel.appendChild(label);
	tdInput.appendChild(input);
	tr.appendChild(thLabel);
	tr.appendChild(tdInput);
	container.appendChild(tr);

	input.focus();
});
});


