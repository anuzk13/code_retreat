import * as FetchService from './fetch_service.js';


const loadGame = () => {
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
                .then(game => 
                    pollGameState(game.id)
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
    return FetchService.getData(`game/${gameId}`).then(game => {
        if (game.active) {
            renderGame(game);
            return pollGameState(game.id);
        }
        else {
            console.log('game finished')
            return false;
        }
    })
}

const renderGame = (game) => {
    console.log(game);
}

export {loadGame}
