const inputA = document.getElementById('valA');
const inputB = document.getElementById('valB');
const btn = document.getElementById('calcBtn');
const result = document.getElementById('result');

btn.addEventListener('click', function() {
    //input.value zawsze zwraca string, czyli knwersja na typ number
    let valA = Number(inputA.value);
    let valB = Number(inputB.value);

    let suma = valA + valB;

    result.innerHTML = 'Wynik: ' + suma;
});