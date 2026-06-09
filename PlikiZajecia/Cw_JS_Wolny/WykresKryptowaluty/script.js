async function drawChart() {
    //Bitcoin. 7 dni. waluta USD
    const url = 'https://api.coingecko.com/api/v3/coins/bitcoin/market_chart?vs_currency=usd&days=7';

    try {
        const response = await fetch(url);
        const data = await response.json();

        const labels = data.prices.map(item => new Date(item[0]).toLocaleDateString());
        const prices = data.prices.map(item => item[1]);

        //rysowanie wykresu
        const ctx = document.getElementById('btcChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Cena Bitcoin (USD)',
                    data: prices,
                    borderColor: 'rgb(247, 147, 26)',
                    backgroundColor: 'rgba(247, 147, 26, 0.1)',
                    fill: true,
                    tension: 0.3 //lekkie zaokrąglenie linii
                }]
            }
        });

    } catch (error) {
        console.error("Błąd rysowania wykresu:", error);
    }
}

drawChart();