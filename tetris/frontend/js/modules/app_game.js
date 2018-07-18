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
            renderGame(gameStatus);
            boardElement.classList.remove('invisible');
            if (!gameStatus.isCurrentPlayer) {
                return pollGameState(gameId);
            } else {
                setGameTurn(gameStatus, gameId);
            }
        }
    });

const pollGameState = (gameId) =>
    FetchService.getData(`game/${gameId}`).then(gameStatus => {
        if (!gameStatus.isCurrentPlayer) {
            return pollGameState(gameId);
        } else {
            renderGame(gameStatus);
            setGameTurn(gameStatus, gameId);
        }
    });

const selectCell = (index, gameId, playerSymbol) => {
    document.getElementsByClassName('board-cell')[index].innerHTML = playerSymbol;
    return FetchService.putData(`play/${gameId}`, {position : index}).then(gameStatus => {
        renderGame(gameStatus);
        endGameTurn();
        return pollGameState(gameId);
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
    if (gameStatus.isCurrentPlayer) {
        boardElement.classList.remove('blocked-board');
    } else {
        boardElement.classList.add('blocked-board');
    }
    const buttons = document.getElementsByClassName('board-cell');
    for (let i = 0; i < 8; i++) {
        const button = buttons[i];
        button.innerHTML = gameStatus.board.positions[i] || '';
        if (gameStatus.board.positions[i]) {
            button.classList.add('blocked-cell');
        }
    }
}

class InvalidPlayState extends Error {
}

export {startGame}
