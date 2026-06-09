const timerKlasowyOutput = document.getElementById('timer-klasowy');
const timerDomknieciowyOutput = document.getElementById('timer-domknieciowy');

// =================================================================
// ZADANIE 1: Timer oparty na Klasie (Problem 'this')
// =================================================================

class TimerKlasowy {
    constructor(element) {
        this.element = element;
        this.sekundy = 0;
    }

    start() {
        //Używamy funkcji strzałkowej żeby nie zgubić this
        //Gdybyśmy użyli 'function() {}' to teraz 'this' wskazywałoby na obiekt Window
        setInterval(() => {
            this.sekundy++;
            this.element.innerHTML = `Licznik (Klasa): ${this.sekundy}`;
        }, 1000);
    }
}

//Uruchomienie Timera Klasowego
const timer1 = new TimerKlasowy(timerKlasowyOutput);
timer1.start();


// =================================================================
// ZADANIE 2: Timer oparty na Domknięciu (Funkcja Fabrykująca)
// =================================================================

function stworzTimerDomknieciowy(element) {
    //Zmienna lokalna -prywatna dzięki domknięciu
    let sekundy = 0;

    //Funkcja wewnętrzna ma dostęp do zmiennej 'sekundy'
    function tick() {
        sekundy++;
        element.innerHTML = `Licznik (Domknięcie): ${sekundy}`;
    }

    setInterval(tick, 1000);
}

//Uruchomienie Timera Domknięciowego
stworzTimerDomknieciowy(timerDomknieciowyOutput);