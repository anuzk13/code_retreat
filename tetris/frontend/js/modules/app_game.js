import * as FetchService from './fetch_service.js';

let gameElement;

const startGame = (ge) => {
    gameElement = ge;
    const hash = new URL(window.location.href).hash.substring(1);
    try {
        const pData = hash ? JSON.parse(decodeURIComponent(hash)) : null;
        const pToken = pData ? pData.playerToken : null;
        if (pToken) {
            FetchService.setToken(pToken);
            const gameId = pData.gameId;
            if (gameId) {
                return pollGameState(gameId);
            } else {
                return FetchService.postData('game', {})
                .then(
                    gameStatus => pollGameState(gameStatus.gameId)
                );
            }
        } else {
            window.location.replace(`../..`);
        }
    }
    catch(error) {
        window.location.replace(`../..`);
    }
}

const pollGameState = (gameId) => {
    return FetchService.getData(`game/${gameId}`).then(gameStatus => {
        if (gameStatus.active) {

        }
        else {
            console.log('game ended')
        }
    })
}


export {startGame}
