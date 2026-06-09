const map = L.map('map').setView([0, 0], 2);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© OpenStreetMap'
}).addTo(map);

async function getUsers() {
    try {
        const response = await fetch('https://jsonplaceholder.typicode.com/users');
        const users = await response.json();

        users.forEach(user => {
            //Wyciągamy współrzędne z obiektu user
            const lat = user.address.geo.lat;
            const lng = user.address.geo.lng;
            
            //Dodajemy marker (Popup)
            L.marker([lat, lng]).addTo(map)
                .bindPopup(`<b>${user.name}</b><br>Miasto: ${user.address.city}`);
        });

    } catch (error) {
        console.error("Błąd ładowania mapy:", error);
    }
}

getUsers();