<?php

namespace UA\MatchBundle\MatchGenerator;

class DeviLanDefault {

	public function generation($gameId) {
		//Recovery team with game = gameId
		$em = $this->getDoctrine()->getManager();
		$game = $em->getRepository('UAMatchBundle:game')->findById($gameId);
		$team = $em->getRepository('UAMatchBundle:Match\team')->findByGame($game);

		
	}
}