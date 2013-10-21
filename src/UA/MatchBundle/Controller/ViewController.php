<?php

namespace UA\MatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ViewController extends Controller
{
	public function indexAction() {
		$em = $this->getDoctrine()->getManager();

		$games = $em->getRepository('UAMatchBundle:game')->findAll();
		return $this->render('UAMatchBundle:MatchView:gameSelection.html.twig', array('games' => $games));
	}

	public function viewgameAction($id) {
		$em = $this->getDoctrine()->getManager();
		
		$Matchs = $em->getRepository('UAMatchBundle:Match\matchs')->findByGame($id);
		usort($Matchs, array($this, 'cmp'));

		return $this->render('UAMatchBundle:MatchView:viewMatchs.html.twig', array('Matchs' => $Matchs));
	}
	
	public function teamAction($game, $page) {
		$em = $this->getDoctrine()->getManager();

		//find 5 teams depending on the game selected and the page
		$teams = $em->getRepository('UAMatchBundle:Match\team')->findBy($game ? array('game' => $game) : array(),
																		array('id' => 'DESC'),
																		5,
																		($page-1)*5);
		//recovery the game list
		$games = $em->getRepository('UAMatchBundle:game')->findAll();

		//count the number of team for generate pages
		$nbrTeam = $em->getRepository('UAMatchBundle:Match\team')->getCount(array($game));

		return $this->render('UAMatchBundle:TeamView:index.html.twig', array(	'teams' => $teams,
																				'games' => $games,
																				'gameSelect' => $game,
																				'pageActive' => $page,
																				'nbrPage' => ceil($nbrTeam/10)));
	}

	/*
	 * Function for sort array by Object Property 'pool'
	 */
	private function cmp($a, $b) {
		return strcmp($a->getTeamA()->getPool(), $b->getTeamA()->getPool());
	}
}