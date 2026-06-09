function upieczPizze(rodzaj) {
    return new Promise((resolve, reject) => {
        console.log(`👨‍🍳 Kuchnia: Przyjąłem zamówienie na ${rodzaj}...`);

        setTimeout(() => {
            //30% szans na przypalenie
            if (Math.random() < 0.3) {
                reject(new Error("Spalona! Nie wydajemy."));
            } else {
                resolve(`Pizza ${rodzaj} gotowa!`);
            }
        }, 3000); //Czas pieczenia
    });
}

async function zlozZamowienie() {
    console.log("Klient: Poproszę pizzę Hawajską.");
    
    try {
        const wynik = await upieczPizze("Hawajska");
        console.log("Kelner: " + wynik);
        console.log("Klient: Dzięki, smacznego!");
    } catch (error) {
        console.error("Kuchnia: " + error.message);
        console.log("Klient: Trudno, idę na kebaba.");
    }
}