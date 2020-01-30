const holes = document.getElementsByClassName('hole');
const scoreBoard = document.getElementById('score');
const moles = document.getElementsByClassName('mole');
const startButton = document.getElementById('start-button');
let lastHole;
let timeUp = false;
let score = 0;

startButton.addEventListener('click', () => {
    startGame();
});

function randomTime(min, max) {
    return Math.round(Math.random() * (max - min) + min);
}

function randomHole(holes) {
    const index = Math.floor(Math.random() * holes.length);
    const hole = holes[index];
    if (hole === lastHole) {
        return randomHole(holes);
    }

    lastHole = hole;
    return hole;
}

function peep() {
    const time = randomTime(500, 2000);
    const hole = randomHole(holes);
    hole.classList.add('up');
    setTimeout(() => {
        hole.classList.remove('up');
        if (!timeUp) {
            peep();
        }
    }, time)
}

function startGame() {
    scoreBoard.textContent = '0';
    score = 0;
    timeUp = false;
    peep();
    setTimeout(() => timeUp = true, 10000)
}

for (let i = 0; i < moles.length; i++) {
    moles[i].addEventListener('click', () => {
        score++;
        holes[i].classList.remove('up');
        scoreBoard.textContent = score;
    })
}
