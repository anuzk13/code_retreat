<?php

namespace App\Controller;

use App\Entity\Game;
use App\View\GameStatus;
use App\Service\GameService;
use App\Exception\PlayerAlreadyInGameException;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use App\Exception\InvalidPositionExecption;


class GameController extends Controller
{
    /**
     * @Route("/game", name="new_game"), methods={"POST"}
     */
    public function newGame(Request $request, GameService $gs, SerializerInterface $serializer)
    {
        $player = $request->attributes->get('_player');
        try {
            $game = $gs->registerGame($player);
            $gameStatus = $gs->getGameStatus($player, $game);
            $jgs = $serializer->serialize($gameStatus, 'json');
            return new Response($jgs);
        } catch (PlayerAlreadyInGameException $ex){
            throw new BadRequestHttpException($ex->getMessage());
        }
    }

    /**
     * @Route("/game/{id}", name="get_game"), methods={"GET"}
     */
    public function getGame($id, Request $request, GameService $gs, SerializerInterface $serializer)
    {
        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->find($id);
        if (!$game) {
            throw $this->createNotFoundException(
                'No game found for id '.$id
            );
        }
        $player = $request->attributes->get('_player');
        $gameStatus = $gs->getGameStatus($player, $game);
        $jgs = $serializer->serialize($gameStatus, 'json');
        return new Response($jgs);
    }

    /**
     * @Route("/symbol/{id}", name="get_available_symbols"), methods={"GET"}
     */
    public function getSymbols($id, GameService $gs)
    {
        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->find($id);
        if (!$game) {
            throw $this->createNotFoundException(
                'No game found for id '.$id
            );
        }
        $symbols = $gs->getAvailableSymbols($game);
        return $this->json($symbols);
    }

    /**
     * @Route("/set_symbol/{id}", name="set_game_symbol"), methods={"PUT"}
     */
    public function setSymbol($id, Request $request, GameService $gs, SerializerInterface $serializer)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $game = $entityManager->getRepository(Game::class)->find($id);
        if (!$game) {
            throw $this->createNotFoundException(
                'No game found for id '.$id
            );
        }
        $pOneSymbol = $request->attributes->get('json_body')['symbol'];
        $pTwoSymbol = $gs->getPlayerTwoSymbol($pOneSymbol);
        $game->setPOneSymbol($pOneSymbol);
        $game->setPTwoSymbol($pTwoSymbol);
        $entityManager->flush();
        $jsonGame = $serializer->serialize($game, 'json');
        return new Response($jsonGame);
    }

    /**
     * @Route("/play/{id}", name="set_game_symbol"), methods={"PUT"}
     */
    public function playPiece($id, Request $request, GameService $gs, SerializerInterface $serializer)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $game = $entityManager->getRepository(Game::class)->find($id);
        if (!$game) {
            throw $this->createNotFoundException(
                'No game found for id '.$id
            );
        }
        $position = (int) $request->attributes->get('json_body')['position'];
        $positions = $game->getBoard()->getPositions();
        if ($positions[$position]) {
            throw new InvalidPositionExecption('there is already a piece in this cell');
        } else {
            $player = $request->attributes->get('_player');
            $symbol = $gs->getPlayerSymbol($player, $game);
            $positions[$position] = $symbol;
            $game->getBoard()->setPositions($positions);
            $active_player = $game->getActivePlayer() === Game::ACTIVE_PLAYER_ONE ? Game::ACTIVE_PLAYER_TWO : Game::ACTIVE_PLAYER_ONE;
            $game->setActivePlayer($active_player);
            $entityManager->flush();
            $gameStatus = $gs->getGameStatus($player, $game);
            $jgs = $serializer->serialize($gameStatus, 'json');
            return new Response($jgs);
        }
    }
}
