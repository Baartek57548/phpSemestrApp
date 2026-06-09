const tableBody = document.getElementById('table-body');
const plnInput = document.getElementById('pln-input');
const btcResult = document.getElementById('btc-result');
const chartContainer = document.querySelector('.chart-container');

let coinsData = []; // Tutaj przechowamy pobrane dane
let myChart = null; // Zmienna na instancję wykresu

// 1. Pobieranie danych z API (Top 50)
async function getCoins() {
    try {
        const response = await fetch('https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=50&page=1');
        coinsData = await response.json();
        renderTable(coinsData);
    } catch (error) {
        console.error("Błąd API:", error);
        alert("Nie udało się pobrać danych kryptowalut!");
    }
}

// 2. Wyświetlanie tabeli
function renderTable(data) {
    tableBody.innerHTML = '';
    data.forEach(coin => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><img src="${coin.image}" width="25"></td>
            <td>${coin.name}</td>
            <td>$${coin.current_price}</td>
            <td style="color: ${coin.price_change_percentage_24h >= 0 ? 'green' : 'red'}">
                ${coin.price_change_percentage_24h.toFixed(2)}%
            </td>
        `;
        // Kliknięcie wiersza ładuje wykres
        row.addEventListener('click', () => loadChart(coin.id, coin.name));
        tableBody.appendChild(row);
    });
}

// 3. Obsługa Wykresu (Chart.js)
async function loadChart(id, name) {
    chartContainer.classList.add('active'); // Pokaż kontener
    
    // Pobierz historię z 7 dni
    const res = await fetch(`https://api.coingecko.com/api/v3/coins/${id}/market_chart?vs_currency=usd&days=7`);
    const data = await res.json();
    
    // API zwraca tablice [timestamp, price]. Musimy to rozdzielić.
    const prices = data.prices.map(item => item[1]);
    const dates = data.prices.map(item => new Date(item[0]).toLocaleDateString());

    const ctx = document.getElementById('cryptoChart').getContext('2d');

    // Jeśli wykres już istnieje, musimy go zniszczyć przed narysowaniem nowego
    if (myChart) {
        myChart.destroy();
    }

    myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: `Historia ceny ${name} (7 dni)`,
                data: prices,
                borderColor: 'blue',
                fill: false,
                tension: 0.1
            }]
        }
    });
}

// 4. Kalkulator (PLN -> BTC)
plnInput.addEventListener('input', (e) => {
    const pln = parseFloat(e.target.value);
    const btcCoin = coinsData.find(c => c.symbol === 'btc');
    
    if (pln > 0 && btcCoin) {
        // Zakładamy uproszczony kurs USD/PLN = 4.0 (w realnej apce trzeba by pobrać)
        const usdPrice = btcCoin.current_price;
        const plnPrice = usdPrice * 4.0; 
        
        const result = pln / plnPrice;
        btcResult.textContent = `${result.toFixed(6)} BTC`;
    } else {
        btcResult.textContent = "0.00 BTC";
    }
});

// 5. Sortowanie
document.getElementById('sort-asc').addEventListener('click', () => {
    const sorted = [...coinsData].sort((a, b) => a.current_price - b.current_price);
    renderTable(sorted);
});

document.getElementById('sort-desc').addEventListener('click', () => {
    const sorted = [...coinsData].sort((a, b) => b.current_price - a.current_price);
    renderTable(sorted);
});

// Start aplikacji
getCoins();