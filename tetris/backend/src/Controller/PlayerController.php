<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Game;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\PlayerService;

class PlayerController extends Controller
{
    /**
     * @Route("/player", name="new_player"), methods={"POST"}
     */
    public function newPlayer(Request $request, PlayerService $ps)
    {
        $body = $request->attributes->get('json_body');
        $player = $ps->registerPlayer($body['name']);
        $playerToken = $ps->playerToken($player);
        $gRep = $this->getDoctrine()->getRepository(Game::class);
        $game = $gRep->getActiveGame($player);
        return $this->json([
            'name' => $player->getName(),
            'token' => $playerToken,
            'active_game_id' => $game ? $game->getId() : null
        ]);
    }
}
