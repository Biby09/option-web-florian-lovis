const barreRecherche = document.querySelector('.search-input');
const suggestionsBox = document.getElementById('suggestions');

if (barreRecherche && suggestionsBox) {
    barreRecherche.addEventListener('input', async (event) => {
        const query = event.target.value.trim();

        if (query.length === 0) {
            suggestionsBox.hidden = true;
            suggestionsBox.innerHTML = '';
            return;
        }

        try {
            const response = await fetch('/api/recherche.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ query })
            });

            const data = await response.json();

            if (!data.length) {
                suggestionsBox.innerHTML = '<button type="button" class="suggestion-item">Aucune recette trouvée</button>';
                suggestionsBox.hidden = false;
                return;
            }

            suggestionsBox.innerHTML = data
                .map(recette => `
                    <button type="button" class="suggestion-item" data-slug="${recette.slug}">
                        ${recette.titre}
                    </button>
                `)
                .join('');

            suggestionsBox.hidden = false;

            suggestionsBox.querySelectorAll('.suggestion-item').forEach(button => {
                button.addEventListener('click', () => {
                    const slug = button.dataset.slug;
                    if (slug) {
                        window.location.href = `/recette/${encodeURIComponent(slug)}`;
                    }
                });
            });
        } catch (error) {
            console.error('Erreur de recherche :', error);
        }
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.search-wrap')) {
            suggestionsBox.hidden = true;
        }
    });
}

barreRecherche.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
        window.location.href = '/?query=' + encodeURIComponent(barreRecherche.value.trim());
    }
});