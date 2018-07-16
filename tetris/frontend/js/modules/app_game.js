import * as FetchService from './fetch_service.js';

let game;

function loadGame() {
    const hash = new URL(window.location.href).hash.substring(1);
    try {
        const pData = hash ? JSON.parse(decodeURIComponent(hash)) : null;
        const pToken = pData ? pData.playerToken : null;
        if (pToken) {
            FetchService.setToken(pToken);
            const gameId = pData.gameId;
            if (gameId) {
                return FetchService.getData(`game/${gameId}`)
                .then(response => {
                    game = response;
                })
            } else {
                return FetchService.postData('game', {})
                .then(response => {
                    game = response;
                });
            }
        } else {
            window.location.replace(`../..`);
        }
    }
    catch(error) {
        window.location.replace(`../..`);
    }
}

export {loadGame}
