document.addEventListener("DOMContentLoaded", () => {
    const searchForm = document.getElementById("search-form");
    const searchBox = document.getElementById("search-box");
    const resultsDiv = document.getElementById("search-results");
    const genreButtons = document.querySelectorAll(".genre-buttons button");

    // Suggestions
    const suggestionsBox = document.createElement("div");
    suggestionsBox.id = "suggestions-box";
    suggestionsBox.style.position = "absolute";
    suggestionsBox.style.background = "#fff";
    suggestionsBox.style.border = "1px solid #ccc";
    suggestionsBox.style.zIndex = "1000";
    suggestionsBox.style.width = searchBox ? searchBox.offsetWidth + "px" : "100%";
    suggestionsBox.style.display = "none";
    if (searchBox) searchBox.parentNode.appendChild(suggestionsBox);

    if (searchBox) {
        searchBox.addEventListener("input", () => {
            const query = searchBox.value.trim();
            if (query.length < 2) {
                suggestionsBox.style.display = "none";
                return;
            }

            fetch(`suggestions.php?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsBox.innerHTML = "";
                    if (data.length === 0) {
                        suggestionsBox.style.display = "none";
                        return;
                    }

                    data.forEach(title => {
                        const div = document.createElement("div");
                        div.textContent = title;
                        div.style.cursor = "pointer";
                        div.style.padding = "10px";
                        div.addEventListener("click", () => {
                            searchBox.value = title;
                            suggestionsBox.style.display = "none";
                            afficherLivres(title);
                        });
                        suggestionsBox.appendChild(div);
                    });

                    suggestionsBox.style.display = "block";
                })
                .catch(err => console.error("Erreur suggestions:", err));
        });
    }

    if (searchForm) {
        searchForm.addEventListener("submit", e => {
            e.preventDefault();
            const query = searchBox.value.trim();
            if (query) afficherLivres(query);
        });
    }

    genreButtons.forEach(button => {
        button.addEventListener("click", () => {
            genreButtons.forEach(btn => btn.classList.remove("active"));
            button.classList.add("active");
            const genre = button.textContent.trim();
            afficherLivres(genre);
        });
    });

    // Fade-in animations
    const fadeInElements = document.querySelectorAll(".fade-in");
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    fadeInElements.forEach(el => observer.observe(el));

    // Mobile menu toggle
    const nav = document.querySelector("nav ul");
    const menuToggle = document.createElement("div");
    menuToggle.className = "menu-toggle";
    menuToggle.innerHTML = "☰";
    document.querySelector("header").prepend(menuToggle);

    menuToggle.addEventListener("click", () => {
        nav.classList.toggle("active");
        menuToggle.innerHTML = nav.classList.contains("active") ? "✕" : "☰";
    });

    function afficherLivres(terme) {
        fetch(`./api/search.php?q=${encodeURIComponent(terme)}`)
            .then(res => res.json())
            .then(data => {
                resultsDiv.innerHTML = "";
                if (!Array.isArray(data) || data.length === 0) {
                    resultsDiv.innerHTML = `<p class="error">Aucun résultat trouvé pour "${terme}".</p>`;
                    return;
                }

                const container = document.createElement("div");
                container.className = "book-cards";

                data.forEach(book => {
                    const card = document.createElement("div");
                    card.className = "book-card fade-in";
                    card.innerHTML = `
                        <a href="livre.php?id=${book.id}" class="book-link">
                            <img src="${book.image_url || 'images/defaut.jpg'}" alt="${book.title}">
                            <div class="book-info">
                                <p><strong>${book.title}</strong></p>
                                <p>${book.auteur}</p>
                            </div>
                        </a>
                    `;
                    container.appendChild(card);
                });

                resultsDiv.appendChild(container);

                // Re-observe new fade-in elements
                container.querySelectorAll(".fade-in").forEach(el => observer.observe(el));
            })
            .catch(err => {
                resultsDiv.innerHTML = `<p class="error">Erreur: ${err.message}</p>`;
            });
    }
});




