let saldoKonta = 1000;

function wyplacPieniadze(kwota) {
    return new Promise((resolve, reject) => {
        console.log(`Przetwarzanie wypłaty: ${kwota} PLN...`);
        
        setTimeout(() => {
            //walidacja
            if (kwota <= 0) {
                reject(new Error("Kwota musi być dodatnia!"));
            } else if (kwota > saldoKonta) {
                reject(new Error(`Brak wystarczających środków! Masz tylko ${saldoKonta} PLN.`));
            } else {
                saldoKonta -= kwota;
                resolve(`Wypłacono ${kwota} PLN. Pozostało na koncie: ${saldoKonta} PLN.`);
            }
        }, 2000);
    });
}

async function uruchomBankomat() {
    try {
        const transakcja1 = await wyplacPieniadze(500);
        console.log(transakcja1);

        //To wywoła błąd saldo = 500, chcemy 800
        const transakcja2 = await wyplacPieniadze(800); 
        console.log(transakcja2);

    } catch (err) {
        console.error("Błąd transakcji:", err.message);
    } finally {
        console.log("--- Dziękujemy za skorzystanie z usług banku ---");
    }
}

uruchomBankomat();