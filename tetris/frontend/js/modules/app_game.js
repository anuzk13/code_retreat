import * as FetchService from './fetch_service.js';

let boardElement;

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
    catch(error) {
        window.location.replace(`../..`);
    }
}

const initGame = (gameId) => 
     FetchService.getData(`game/${gameId}`).then(gameStatus => {
        if (!gameStatus.playerSymbol || !gameStatus.playerTwo) {
            window.location.replace(`../..`);
        } else {
            createBoard(gameStatus);
            renderGame(gameStatus);
            if (!gameStatus.active) {
                renderGameEnd(gameStatus);
            }
            else if (!gameStatus.isCurrentPlayer) {
                return pollGameState(gameId);
            } else {
                setGameTurn(gameStatus, gameId);
            }
        }
    });

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

const pollGameState = (gameId) =>
    FetchService.getData(`game/${gameId}`).then(gameStatus => {
        if (!gameStatus.active) {
            renderGame(gameStatus);
            renderGameEnd(gameStatus);
        }
        else if (gameStatus.isCurrentPlayer) {
            renderGame(gameStatus);
            setGameTurn(gameStatus, gameId);
        } else {
            return pollGameState(gameId);
        }
    });

const selectCell = (index, gameId, playerSymbol) => {
    document.getElementsByClassName('board-cell')[index].innerHTML = playerSymbol;
    return FetchService.putData(`play/${gameId}`, {position : index}).then(gameStatus => {
        renderGame(gameStatus);
        endGameTurn();
        if (gameStatus.isWinner) {
            renderWinner()
        } else if (gameStatus.isDraw) {
            renderDraw()
        } else {
            return pollGameState(gameId);
        }
    });
}

const setGameTurn = (gameStatus, gameId) => {
    const buttons = document.getElementsByClassName('board-cell');
    for (let index = 0; index < 9; index++) {
        const button = buttons[index];
        if (!gameStatus.board.positions[index]) {
            button.onclick = () => selectCell(index, gameId, gameStatus.playerSymbol);
        }
    }
}

const endGameTurn = () => {
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
        button.innerHTML = gameStatus.board.positions[i] || '';
        if (gameStatus.gameVictory && gameStatus.gameVictory.includes(i)) {
            console.log(i);
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
    loser.innerHTML =  `Perdedor`;
    loser.className = 'result';
    boardElement.appendChild(loser);
}

export {startGame}
