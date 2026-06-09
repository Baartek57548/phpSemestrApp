class TrafficLight {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.currentState = null;
        this.lights = {}; //Przechowuje dane do div
        this.initDOM();
    }

    initDOM() {
        ['red', 'yellow', 'green'].forEach(color => {
            const el = document.createElement('div');
            el.className = `light ${color}`;
            this.container.appendChild(el);
            this.lights[color] = el;
        });
    }

    start() {
        if(this.currentState) return; //to jest zabezpieczenie przed podwójnym startem
        this.changeState('red');
    }

    changeState(color) {
        this.currentState = color;
        
        //ON All
        Object.values(this.lights).forEach(l => l.classList.remove('active'));
        //OFF present
        this.lights[color].classList.add('active');

        //sekwencja przejsc
        let nextColor = '';
        let duration = 0;

        switch (color) {
            case 'red':
                nextColor = 'green';
                duration = 3000;
                break;
            case 'green':
                nextColor = 'yellow';
                duration = 3000;
                break;
            case 'yellow':
                nextColor = 'red';
                duration = 1000;
                break;
        }

        setTimeout(() => this.changeState(nextColor), duration);
    }
}

const sygnalizacja = new TrafficLight('traffic-light');
document.getElementById('start-btn').addEventListener('click', () => sygnalizacja.start());