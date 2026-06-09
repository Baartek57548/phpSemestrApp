const map = L.map('map').setView([52.0, 19.0], 6); // Środek Polski
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

const listaCelow = document.getElementById('cele-lista');

map.on('click', async (e) => {
    const { lat, lng } = e.latlng;
    
    try {
        //fetch API
        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current_weather=true`;
        const res = await fetch(url);
        const data = await res.json();
        
        const temp = data.current_weather.temperature;
        
        //marker
        L.marker([lat, lng]).addTo(map)
            .bindPopup(`Pogoda: ${temp}°C`)
            .openPopup();

        //Dodanie do listy (DOM)
        const li = document.createElement('li');
        li.innerHTML = `
            <strong>Koordynaty:</strong> ${lat.toFixed(2)}, ${lng.toFixed(2)}<br>
            <strong>Temperatura:</strong> ${temp}°C
            <input type="text" placeholder="Notatka (np. zabrać parasol)">
        `;
        listaCelow.appendChild(li);

    } catch (err) {
        console.error("Nie udało się pobrać pogody:", err);
    }
});