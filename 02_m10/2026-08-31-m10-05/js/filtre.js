const formulaire = document.querySelector('#filtre');
const ville = document.querySelector('#ville');
const liste = document.querySelector('#resultats');

formulaire.addEventListener('change', async () => {
  const parametres = new URLSearchParams({ ville: ville.value });
  const reponse = await fetch(`api/evenements.php?${parametres}`);
  const evenements = await reponse.json();

  liste.innerHTML = '';
  if (evenements.length === 0) {
    liste.innerHTML = '<li>Aucun événement trouvé</li>';
  } else {
    liste.innerHTML = evenements.map(evenement => `
    <li>${evenement.nom} - ${evenement.date} - ${evenement.ville}</li>
    `).join('');
  }
});
