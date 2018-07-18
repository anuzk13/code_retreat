import * as Player from './modules/player.js';
import * as GameLoader from './modules/game_loader.js';
import * as AppGame from './modules/app_game.js';

function registerPlayer(playerName) {
    Player.registerPlayer(playerName);
}

function loadGame(loadElement) {
    GameLoader.loadGame(loadElement);
}

function startGame(board) {
    AppGame.startGame(board);
}

export {registerPlayer, loadGame, startGame};