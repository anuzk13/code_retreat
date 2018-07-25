import * as FetchService from './fetch_service.js';

let boardElement;
let turnStatus;
let working = false;

const startGame = (b) => {
    boardElement = b;
    const hash = new URL(window.location.href).hash.substring(1);
    try {
        const pData = hash ? JSON.parse(decodeURIComponent(hash)) : null;
        const pToken = pData ? pData.playerToken : null;
        if (pToken) {
            FetchService.setToken(pToken);
            const gameId = pData.gameId;
            if (gameId) {
                return initGame(gameId);
            } else {
                window.location.replace(`../..`);
            }
        } else {
            window.location.replace(`../..`);
        }
    }
    catch (error) {
        window.location.replace(`../..`);
    }
}

const initGame = async (gameId) => {
    const gameStatus = await FetchService.getData(`game/${gameId}`);
    if (!gameStatus.playerSymbol || !gameStatus.playerTwo) {
        window.location.replace(`../..`);
    } else {
        createBoard(gameStatus);
        renderGame(gameStatus);
        turnStatus = gameStatus.isCurrentPlayer;
        if (!gameStatus.active) {
            renderGameEnd(gameStatus);
            renderPlayAgain();
        }
        else if (!gameStatus.isCurrentPlayer) {
            return pollGameState(gameId);
        } else {
            setGameTurn(gameStatus, gameId);
            return pollGameState(gameId);
        }
    }
};

const createBoard = (gameStatus) => {
    boardElement.className = 'board';
    const header = document.createElement("h5");
    header.className = 'player-header';
    header.innerHTML = `Jugador - ${gameStatus.playerSymbol}`;
    boardElement.appendChild(header);
    const row = document.createElement("div");
    row.className = 'row';
    for (let index = 0; index < 9; index++) {
        const cell = document.createElement("div");
        cell.className = 'col s4 board-cell';
        row.appendChild(cell)
    }
    boardElement.appendChild(row);
    if (gameStatus.active) {
        const abandonButton = document.createElement("button");
        abandonButton.textContent = "Abandonar Juego";
        abandonButton.className = "abandon-button btn waves-effect waves-light";
        abandonButton.id = "abandonGame";
        abandonButton.onclick = () => abandonGame(gameStatus.gameId);
        boardElement.appendChild(abandonButton);
    }
}

const renderPlayAgain = () => {
    // remove abando button
    const boardElement = document.getElementsByClassName('board')[0];
    const abandonButton = document.getElementById('abandonGame');
    if (abandonButton) {
        abandonButton.remove();
    }
    const replayButtoon = document.createElement("button");
    replayButtoon.textContent = "Jugar otra vez";
    replayButtoon.className = "replay-button btn waves-effect waves-light";
    replayButtoon.id = "replayGame";
    replayButtoon.onclick = () => window.location.replace(`../..`);
    boardElement.appendChild(replayButtoon);
}

const renderGameEnd = (gameStatus) => {
    if (gameStatus.isLoser) {
        renderLoser();
    } else if (gameStatus.isDraw) {
        renderDraw();
    } else if (gameStatus.isWinner) {
        renderWinner();
    }
}

const pollGameState = async (gameId) => {
    let keepPolling = true;
    while (keepPolling) {
        await new Promise(res => window.setTimeout(res, 1000));
        const wasWorking = working;
        const gameStatus = await FetchService.getData(`game/${gameId}`);
        if (working || wasWorking) {
            continue; // ignore result
        }
        if (!gameStatus.active) {
            renderGame(gameStatus);
            renderPlayAgain();
            renderGameEnd(gameStatus);
            keepPolling = false;
        }
        else if (turnStatus != gameStatus.isCurrentPlayer) {
            if (gameStatus.isCurrentPlayer) {
                renderGame(gameStatus);
                setGameTurn(gameStatus, gameId);
            } else {
                endGameTurn();
            }
        }
    }
};

const abandonGame = async (gameId) => {
    document.getElementById('abandonGame').setAttribute('disabled', true);
    boardElement.classList.add('blocked-board');
    await FetchService.putData(`end/${gameId}`);
}

const selectCell = async (index, gameId, playerSymbol) => {
    working = true;
    boardElement.classList.add('blocked-board');
    document.getElementsByClassName('board-cell')[index].innerHTML = playerSymbol;
    for (let c of document.getElementsByClassName('board-cell')) {
        c.onclick = null;
    }
    await FetchService.putData(`play/${gameId}`, { position: index });
    working = false;
}

const setGameTurn = (gameStatus, gameId) => {
    turnStatus = true;
    const buttons = document.getElementsByClassName('board-cell');
    for (let index = 0; index < 9; index++) {
        const button = buttons[index];
        if (!gameStatus.board.positions[index]) {
            button.onclick = () => selectCell(index, gameId, gameStatus.playerSymbol);
        }
    }
}

const endGameTurn = () => {
    turnStatus = false;
    boardElement.classList.add('blocked-board');
    const buttons = document.getElementsByClassName('board-cell');
    for (let index = 0; index < 9; index++) {
        const button = buttons[index];
        button.onclick = null;
    }
}

const renderGame = (gameStatus) => {
    if (gameStatus.isCurrentPlayer && gameStatus.active) {
        boardElement.classList.remove('blocked-board');
    } else {
        boardElement.classList.add('blocked-board');
    }
    const buttons = document.getElementsByClassName('board-cell');
    for (let i = 0; i < 9; i++) {
        const button = buttons[i];
        if (!button.innerHTML || button.innerHTML !== '') {
            button.innerHTML = gameStatus.board.positions[i] || '';
        }
        if (gameStatus.gameVictory && gameStatus.gameVictory.includes(i)) {
            button.classList.add('winner-cell');
        } else if (gameStatus.board.positions[i]) {
            button.classList.add('blocked-cell');
        }
    }
}

const renderWinner = () => {
    const winner = document.createElement("h3");
    winner.innerHTML = `Ganador`;
    winner.className = 'result';
    boardElement.appendChild(winner);
}

const renderDraw = () => {
    const draw = document.createElement("h3");
    draw.innerHTML = 'Empate';
    draw.className = 'result';
    boardElement.appendChild(draw);
}

const renderLoser = () => {
    const loser = document.createElement("h3");
    loser.innerHTML = `Perdedor`;
    loser.className = 'result';
    boardElement.appendChild(loser);
}

export { startGame }
