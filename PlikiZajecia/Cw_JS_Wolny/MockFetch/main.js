//Funkcja udająca fetch (zwraca Promise)
function mockFetch(url) {
    return new Promise((resolve, reject) => {
        console.log(`📡 [Mock] Łączenie z: ${url}...`);

        setTimeout(() => {
            //sumulowanie 90% szans na sukces
            const isSuccess = Math.random() > 0.1;
            
            if (isSuccess) {
                //obiekt response
                const fakeResponse = {
                    ok: true,
                    status: 200,
                    json: async () => [
                        { id: 1, title: "Post testowy A" },
                        { id: 2, title: "Post testowy B" }
                    ]
                };
                resolve(fakeResponse);
            } else {
                reject(new Error("Network Error (Brak internetu)"));
            }
        }, 1500); //opoznienie 1500ms
    });
}

const btn = document.getElementById('btn-mock');
const output = document.getElementById('output');

btn.addEventListener('click', async () => {
    output.innerText = "Ładowanie...";
    
    try {
        const response = await mockFetch('https://fake-api.com/posts');
        
        if (!response.ok) throw new Error("Błąd HTTP");
        
        const data = await response.json();
        output.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
        
    } catch (err) {
        output.innerHTML = `<span style="color:red">Błąd: ${err.message}</span>`;
    }
});