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
    const time = randomTime(500, 1500);
    const hole = randomHole(holes);
    hole.classList.add('up');
    setTimeout(() => {
        hole.classList.remove('up');
        if (!timeUp) {
            peep();
        }
    }, time)
}

for (let i = 0; i < moles.length; i++) {
    moles[i].addEventListener('click', () => {
        score++;
        holes[i].classList.remove('up');
        scoreBoard.textContent = score;
    })
}

const timer = document.getElementById('timer');
let timeLeft = 20;

timer.textContent = timeLeft;

function countdown() {
    setTimeout(() => {
        timeLeft--;
        timer.textContent = timeLeft;
        if (timeLeft > 0) {
            countdown()
        }
    }, 1000);
}

const end = document.getElementById('end');
const endText =document.getElementById('end-text');

const winButton = document.getElementById('win-button');

const winText = 'Félicitations, tu gagnes une place gratuite pour notre spectacle !';
const loseText = 'Retente ta chance pour gagner une place !';

function startGame() {
    scoreBoard.textContent = '0';
    score = 0;
    timeLeft = 20;
    timeUp = false;
    end.classList.remove('display');
    peep();
    setTimeout(() => {
        timeUp = true;
        end.classList.add('display');
        if (score > 15) {
            endText.textContent = winText;
            winButton.classList.add('display');
            setTimeout(() => {
                fetch(
                    '/game',
                    {
                        method: 'post',
                        headers: {
                            'Accept': 'application/json',
                            'Content-type': 'application/json'
                        },
                        body: JSON.stringify({
                            'win': true,
                        }),
                    }
                )
                    .then(function (response) {
                        return response.json()
                    })

                    .then(function (htmlContent) {

                    })
            }, 3000);

        } else {
            endText.textContent = loseText;
        }
    }, 20000);
    countdown();
}

