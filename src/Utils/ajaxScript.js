document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('.form-inline');

    if (searchForm) {
        searchForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const query = document.querySelector('.form-control-search').value;
            const id = searchForm.dataset.id; 
            
            fetch(`/produits/ajax?query=${query}&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    const resultsContainer = document.querySelector('.row-cols-1');
                    resultsContainer.innerHTML = '';

                    data.forEach(produit => {
                        resultsContainer.innerHTML += `
                            <div class="col">
                                <div class="card h-70 bg-light text-black">
                                    <img src="${produit.photo}" class="card-img-top img-fluid fixed-size-image" alt="${produit.nom}" onmouseover="this.style.transform='scale(1.05)'; this.style.transition='transform 0.2s ease-in-out';" onmouseout="this.style.transform='scale(1)';">
                                    <div class="card-body d-flex flex-column">
                                        <h2 class="card-title h4 text-center">${produit.nom}</h2>
                                        <p class="pe-1 ps-1">${produit.description}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                })
                .catch(error => console.error('Error:', error));
        });
    }
});
