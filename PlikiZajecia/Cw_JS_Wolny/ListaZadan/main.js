const loadBtn = document.getElementById('load-tasks');
const list = document.getElementById('task-list');

const API_URL = 'https://jsonplaceholder.typicode.com/todos?_limit=5';

async function fetchTasks() {
   try {
       const response = await fetch(API_URL);

       //sprawdzenie statusu HTTP
       if (!response.ok) {
           throw new Error(`Błąd HTTP: ${response.status}`);
       }

       //Pobranie danych JSON
       const data = await response.json();
       renderTasks(data);

   } catch (error) {
       console.error("Wystąpił błąd:", error);
       alert("Nie udało się pobrać zadań! Sprawdź konsolę.");
   }
}

function renderTasks(tasks) {
   list.innerHTML = '';

   tasks.forEach(task => {
       const li = document.createElement('li');
       
       //API JSONPlaceholder zwraca pole title a nie name
       li.textContent = task.title;

       //Oznaczenie zadań wykonanych
       if (task.completed) {
           li.style.textDecoration = 'line-through';
           li.style.color = 'gray';
       }

       list.appendChild(li);
   });
}

loadBtn.addEventListener('click', fetchTasks);