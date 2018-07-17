import * as Player from './modules/player.js';
import * as Game from './modules/app_game.js';

function registerPlayer(playerName) {
    Player.registerPlayer(playerName);
}

function startGame(selectElement) {
    Game.loadGame(selectElement)
}

export {registerPlayer, startGame};