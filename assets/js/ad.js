$("#add-image").click(function () {
	// Récupère le nbr de zone form-group = nombre d'images affichées
	// le + force le cast d'une string vers un integer
	const index = +$("#widgets-counter").val();
	// Récupération du prototype (généré par symfony)
	const tmpl = $("#ad_images")
		.data("prototype")
		.replace(/__name/g, index);
	// injection du code
	$("#ad_images").append(tmpl);
	// Incrément du compteur
	$("#widgets-counter").val(index + 1);
	// Gestion du bouton supprimer
	handleDeleteButtons();
});

function handleDeleteButtons() {
	$('button[data-action="delete"]').click(function () {
		const target = this.dataset.target;
		$(target).remove();
	});
}

function updateCounter() {
	const count = +$("#ad_images div.form-group").length;
	$("#widgets-counter").val(count);
}

updateCounter();
handleDeleteButtons();
