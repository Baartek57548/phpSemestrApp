import { dodaj, pomnoz, PI } from './mathUtils.js';

console.log("Start aplikacji modułowej...");
console.log(`Wartość PI: ${PI}`);

const wynikDodawania = dodaj(10, 5);
console.log(`10 + 5 = ${wynikDodawania}`);

const wynikMnozenia = pomnoz(4, 4);
console.log(`4 * 4 = ${wynikMnozenia}`);