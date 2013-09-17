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
		
		$Matchs = $em->getRepository('UAMatchBundle:Match\matchs')->findBy(array('game' => $id));
		usort($Matchs, array($this, 'cmp'));
		return $this->render('UAMatchBundle:MatchView:viewMatchs.html.twig', array('Matchs' => $Matchs));
	}
	
	/*
	 * Function for sort array by Object Property 'pool'
	 */
	private function cmp($a, $b) {
		return strcmp($a->getTeamA()->getPool(), $b->getTeamA()->getPool());
	}
}