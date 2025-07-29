document.addEventListener('DOMContentLoaded', function () {
    const paysSelect = document.querySelector('#cave_bouteille_pays');
    const regionSelect = document.querySelector('#cave_bouteille_region');
    console.log('test js');
    if (paysSelect) {
        console.log('test if');
        paysSelect.addEventListener('change', function () {
            const paysId = this.value;
            console.log('test');

            fetch(`/regions/by-pays/${paysId}`)
                .then(response => response.json())
                .then(data => {
                    regionSelect.innerHTML = '<option value="">Choisissez une région</option>';
                    data.forEach(region => {
                        const option = document.createElement('option');
                        option.value = region.id;
                        option.textContent = region.region;
                        regionSelect.appendChild(option);
                    });
                });
        });
    }
});