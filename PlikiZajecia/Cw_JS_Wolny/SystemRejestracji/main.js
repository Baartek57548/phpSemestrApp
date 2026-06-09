const ageInput = document.getElementById('age');
const vipInput = document.getElementById('isVip');
const checkBtn = document.getElementById('checkBtn');
const statusSpan = document.getElementById('status');

checkBtn.addEventListener('click', function() {
    //Pobranie danych (symulacja stringów)
    let wiekRaw = ageInput.value; 
    let vipRaw = vipInput.checked.toString(); //true lub false

    
    //1. Konwersja wieku na liczbę
    //(Inaczej "2" > "18" mogłoby dać błędny wynik w zależności od sortowania stringów)
    let wiek = Number(wiekRaw);
    let czyPelnoletni = wiek >= 18;

    //2. Konwersja statusu VIP
    //String "false"w js jest wartością truthy! Musimy porównać stringi.
    let czyVip = (vipRaw === 'true');

    console.log("Logika:", { czyPelnoletni, czyVip });

    if (czyPelnoletni && czyVip) {
        statusSpan.innerHTML = "Wstęp do strefy VIP przyznany.";
        statusSpan.style.color = "green";
    } else {
        statusSpan.innerHTML = "Brak dostępu.";
        statusSpan.style.color = "red";
    }
});