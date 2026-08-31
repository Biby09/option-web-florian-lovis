const formulaire = document.querySelector('#filtre');
const ville = document.querySelector('#ville');
const liste = document.querySelector('#resultats');

formulaire.addEventListener('change', async () => {
  const parametres = new URLSearchParams({ ville: ville.value });
  /* à vous : demander api/evenements.php avec ces paramètres */
});
