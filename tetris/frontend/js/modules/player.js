import * as FetchService from './fetch_service.js';

function registerPlayer(playerName) {
    return FetchService.postData('player', {name: playerName})
                        .then(response => {
                            const playerToken = response.token;
                            const gameId = response.active_game_id || '';
                            const playerData = { gameId, playerToken }
                            const pEndodedData = encodeURIComponent(JSON.stringify(playerData))
                            window.location.replace(`views/load_game.html#${pEndodedData}`);
                        });
}

export {registerPlayer}