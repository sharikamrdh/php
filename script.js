document.addEventListener("DOMContentLoaded", () => {
    const searchForm = document.getElementById("search-form");
    const searchBox = document.getElementById("search-box");
    const genreButtons = document.querySelectorAll(".genre-buttons button");

    // Gestion de la soumission du formulaire de recherche
    if (searchForm) {
        searchForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const query = searchBox.value.trim();
            if (query) afficherLivres(query);
        });
    }

    // Gestion des boutons de genre
    genreButtons.forEach(button => {
        button.addEventListener("click", () => {
            genreButtons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");
            afficherLivres(button.textContent.trim());
        });
    });
});

function afficherLivres(terme) {
    fetch(`search.php?q=${encodeURIComponent(terme)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur HTTP");
            }
            return response.json();
        })
        .then(data => {
            if (!Array.isArray(data)) {
                throw new Error(data.error || "Réponse invalide du serveur");
            }

            const resultsDiv = document.getElementById("search-results");
            resultsDiv.innerHTML = "";

            if (data.length === 0) {
                resultsDiv.innerHTML = `<p class="error">Aucun résultat trouvé pour « ${terme} ».</p>`;
                return;
            }

            const bookGrid = document.createElement("div");
            bookGrid.className = "book-cards";

            data.forEach(book => {
                const card = document.createElement("div");
                card.className = "book-card fade-in";

                // Ajout du lien vers livre.php
                card.innerHTML = `
                    <a href="livre.php?id=${book.id}" class="book-link">
                        <img src="${book.image_url || 'images/defaut.jpg'}" alt="${book.title}">
                        <div class="book-info">
                            <p><strong>${book.title}</strong></p>
                            <p>${book.auteur}</p>
                        </div>
                    </a>
                `;

                bookGrid.appendChild(card);
            });

            resultsDiv.appendChild(bookGrid);
        })
        .catch(err => {
            document.getElementById("search-results").innerHTML = `<p class="error">${err.message}</p>`;
        });
}

// Event listener pour le formulaire
document.getElementById("search-form").addEventListener("submit", function(e) {
    e.preventDefault();
    const terme = document.getElementById("search-input").value.trim();
    if (terme) {
        afficherLivres(terme);
    }
});




