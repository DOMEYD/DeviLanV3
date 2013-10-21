<?php

namespace UA\MatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
//Entity Doctrine
use UA\MatchBundle\Entity\Match\matchs;
use UA\MatchBundle\Entity\Match\team;
use UA\MatchBundle\Entity\Match\Players;
use UA\MatchBundle\Entity\game;
//Forms
use UA\MatchBundle\Form\Match\teamType;
use UA\MatchBundle\Form\Match\PlayersType;
use UA\MatchBundle\Form\gameType;


class AdminController extends Controller
{
	public function indexAction() {

		return $this->render('UAMatchBundle:Admin:index.html.twig');
	}

	/**
	 * @Secure(roles="ROLE_ARBITRE")
	 */
	public function generateAction($etape, $gameID) {
		$em = $this->getDoctrine()->getManager();

		//Verify if Matchs for this gameID weren't already generate
		if(!empty($gameID)) {
			$MatchsAlreadyGenerate = $em->getRepository('UAMatchBundle:Match\matchs')->findBy(array('game' => $gameID));
		}

		/*
		 * select the game
		 */
		if($etape == 0 && empty($gameID)) {
			$games = $em->getRepository('UAMatchBundle:game')->findAll();
			return $this->render('UAMatchBundle:Admin:generate.selectGame.html.twig', array('games' => $games));
		}
		/*
		 * Advice the number of pool
		 */
		elseif($etape == 1 && !empty($gameID) && count($MatchsAlreadyGenerate) == 0) {
			$game = $em->find('UAMatchBundle:game', $gameID);

			//Calcul number of pool
			$nbrTeams = count($game->getTeams());
			$nbrPoolAdvisable = ceil($nbrTeams / 4);

			return $this->render('UAMatchBundle:Admin:generate.pool.html.twig', array(	'game' => $game,
																						'nbrTeams' => $nbrTeams,
																						'nbrPoolAdvisable' => $nbrPoolAdvisable));
		}
		elseif($etape == 2 && !empty($gameID) && count($MatchsAlreadyGenerate) == 0) {
			//Generation of pools
			$Teams = $em->getRepository('UAMatchBundle:Match\team')->findBy(array('game' => $gameID, 'present' => true));
			$i = 1;
			$nbrPool = $this->getRequest()->request->get('nbrPool');
			foreach ($Teams as $team) {
				$team->setPool($i);
				if($i == $nbrPool) {
					$i = 1;
				}
				else {
					$i++;
				}
			}
			$em->flush();

			//Generation of matchs
			foreach ($Teams as $id1 => $eq1) {
				foreach ($Teams as $id2 => $eq2) {
					if($Teams[$id1]->getId() < $Teams[$id2]->getId() && $Teams[$id1]->getPool() == $Teams[$id2]->getPool()) {
						//print_r($Teams[$id1]->getId() . ' vs ' . $Teams[$id2]->getId() . ' in ' . $Teams[$id2]->getPool() . '<br>');
						$Matchs = new Matchs();
						$Matchs->setTeamA($Teams[$id1]);
						$Matchs->setTeamB($Teams[$id2]);
						$Matchs->setGame($Teams[$id1]->getGame());
						$Matchs->setStep('Pool');
						$em->persist($Matchs);
						$em->flush();
					}
				}
			}
			return $this->render('UAMatchBundle:Admin:generation.html.twig', array(	'message' => 'GenerateSuccessful', 
																					'game' => $Teams[0]->getGame()));
		}

		/* Gestion of errors */
		if($etape > 2) {
			$error = 'NotExistingStep';
		}
		elseif (count($MatchsAlreadyGenerate) > 0) {
			$error = 'MatchsAlreadyGenerate';
		}
		else {
			$error = 'UnexpectedError';
		}
		return $this->render('UAMatchBundle:Admin:error.html.twig', array('Error' => $error));
	}

	/**
	 * @Secure(roles="ROLE_ARBITRE")
	 */
	public function refereeAction($MatchID) {
		$em = $this->getDoctrine()->getManager();
		$Match = $em->getRepository('UAMatchBundle:Match\matchs')->find($MatchID);

		if(count($Match) == 1){ //Test of Match ID

			if($this->getRequest()->getMethod() == 'POST') {
				/* Update the scores */
				$Match->setScoreA($this->getRequest()->request->get('sc1'));
				$Match->setScoreB($this->getRequest()->request->get('sc2'));
				$em->persist($Match);
				$em->flush();

				/* Found actual step */
				$Step = (int) $Match->getStep();
				$braket = substr($Match->getStep(), -1);

				/*
				 * Generate the rest of the tree according to the step
				 */
				if($Match->getStep() == 'Pool') {
					$MatchRemaining = $em->getRepository('UAMatchBundle:Match\matchs')->findBy(array('game' => $Match->getGame(), 'scoreA' => null, 'scoreB' => null));
					
					/*
					 * End of pool's matchs : Generate tree of match
					 */
					if(count($MatchRemaining) == 0) {
						/* Calculate number of Elite team and number of turn */
						$nbrTeams = count($em->getRepository('UAMatchBundle:Match\team')->findBy(array('game' => $Match->getGame()->getId(), 'present' => true)));
						$nbrTeamAllow = pow(2, ceil(log($nbrTeams) / log(2))-1);
						$NbrTurn = ceil(log($nbrTeamAllow) / log(2));

						/* Calcul scores of teams */
						$MatchScore = $em->getRepository('UAMatchBundle:Match\matchs')->findAll();
						$tableScore = array();
						foreach ($MatchScore as $match) {
							/* Define who is the winner and who is the loser*/
							$match->getScoreA() > $match->getScoreB() ? $eqW = $match->getTeamA() : $eqW = $match->getTeamB();
							$match->getScoreA() > $match->getScoreB() ? $eqL = $match->getTeamB() : $eqL = $match->getTeamA();
							
							$teamName = $eqW->getName();
							$keyTable = FALSE;
							foreach ($tableScore as $key => $value) {
								if($value[0] == $teamName){
									$keyTable = $key;
								}
							}
							
							if($keyTable == FALSE) {
								$tableScore[] = array($teamName, '$eqW', 1);
							}
							else {
								$tableScore[$keyTable][2] += 1;
							}
						}
						print_r($tableScore);
						return ;
					}
				}
				elseif($Step != 2) {
					$ContinuationMatch = $em->getRepository('UAMatchBundle:Match\matchs')->findOneBy(array('game' => $Match->getGame(), 'scoreB' => null, 'step' => ($Step/2).$braket));
					$sc1 > $sc2 ? $eqW = $Match->getTeamA() : $eqW = $Match->getTeamB();
					if(count($ContinuationMatch) > 0) {
						$ContinuationMatch->setTeamB($eqW);
					} else {
						$ContinuationMatch = new Matchs();
						$ContinuationMatch->setGame($Match->getGame());
						$ContinuationMatch->setTeamA($eqW);
						$ContinuationMatch->setStep(($Step/2) . $braket);
						$em->persist($ContinuationMatch);
						$em->flush();
					}
				}
				elseif($Step == 2) {
					//FINAL
				}
				else {
					//WINNER IS ... !
				}
				return $this->redirect($this->generateUrl('UAMatchBundle_ViewMatchforGame', array('id' => $Match->getGame()->getId())));
			}
			else {
				return $this->render('UAMatchBundle:Admin:ArbitrageForm.html.twig', array('Match' => $Match));
			}
		}
		else {
			$error = 'ArbitrageMatchNotFound';
			return $this->render('UAMatchBundle:Admin:error.html.twig', array('Error' => $error));
		}
	}

	/**
	 * @Secure(roles="ROLE_ARBITRE")
	 */
	public function playeraddAction($id) {
		//init
		$em = $this->getDoctrine()->getManager();
		$Player = new Players();
		$form = $this->createForm(new PlayersType, $Player);

		//Recovery entity team
		$team = $em->find('UAMatchBundle:Match\team', $id);

		//Submit form
		if($this->getRequest()->getMethod() == 'POST') {
			$form->bind($this->getRequest());

			if ($form->isValid()) {
				//Complete team
				$Player->setTeam($team);

				//Create a new user able to connect on the site
				$userManager = $this->container->get('fos_user.user_manager');
				$user = $userManager->createUser();
				$user->setUsername($Player->getUsername());
				$user->setEmail( strtolower($Player->getUsername()) . "@devi.lan" );
				$user->setPlainPassword($team->getPassword());
				$userManager->updateUser($user);

				//link the fosUser account and the player register
				$Player->setAccount($user);

				//Save in Database
				$em->persist($Player);
				$em->flush();

				//Empty form
				$form = $this->createForm(new PlayersType);
			}
		}
		//Recovery of player from the team
		$players = $em->getRepository('UAMatchBundle:Match\Players')->findByTeam($team);

		return $this->render('UAMatchBundle:Admin:team.PlayerAdd.html.twig', array(	'teamInfo' => $team, 
																					'Players' => $players, 
																					'form' => $form->createView()));
	}

	/**
	 * @Secure(roles="ROLE_ARBITRE")
	 */
	public function playerdeleteAction($id) {
		//Recovery entity Players
		$em = $this->getDoctrine()->getManager();
		$player = $em->getRepository('UAMatchBundle:Match\Players')->find($id);

		//ID of the team Recovery
		$idTeam = $player->getTeam()->getId();

		//Delete it and the FOSUser
		$em->remove($player);
		$em->flush();

		//redirect to playerAdd
		return $this->redirect($this->generateUrl('UAMatchAdmin_PlayerAdd', array('id' => $idTeam)));
	}

	/**
	 * @Secure(roles="ROLE_ARBITRE")
	 */
	public function teamAction($game, $page) {
		//initialize
		$em = $this->getDoctrine()->getManager();
		$newTeam = new team();
		$form = $this->createForm(new teamType, $newTeam);

		if ($this->getRequest()->getMethod() == 'POST') {
			$form->bind($this->getRequest());

			if ($form->isValid()) {
				//Verifing if a team with this name exist in this game
				$TeamExist = $em->getRepository('UAMatchBundle:Match\team')->findOneBy(array('name' => $newTeam->getName(),
																							 'game' => $newTeam->getGame()));
				if(!empty($TeamExist)) {
					return $this->render('UAMatchBundle:Admin:error.html.twig', array('Error' => 'teamAlreadyExist'));
				}

				//Complete missing values
				$newTeam->setPresent(False);
				$newTeam->setPassword(substr(sha1($newTeam->getName() + time()), 0, 6)); //password from 6 first character sha1 of TeamName and time

				//Save in Database
				$em->persist($newTeam);
				$em->flush();

				//Redirect to the AddPlayer forms
				return $this->redirect( $this->generateUrl('UAMatchAdmin_PlayerAdd', array('id' => $newTeam->getId())) );
			}
		}

		$Teams = $em->getRepository('UAMatchBundle:Match\team')->findBy($game ? array('game' => $game) : array(),
																		array('id' => 'DESC'),
																		5, ($page-1)*5);

		$nbrTeams = $em->getRepository('UAMatchBundle:Match\team')->getCount(array($game));
		$Games = $em->getRepository('UAMatchBundle:game')->findAll();

		return $this->render('UAMatchBundle:Admin:team.html.twig', array('form' => $form->createView(), 
																		 'Teams' => $Teams,
																		 'page' => $page,
																		 'gameSelected' => $game,
																		 'nbrPage' => ceil($nbrTeams/5),
																		 'games' => $Games));
	}

	/**
	 * @Secure(roles="ROLE_ARBITRE")
	 */
	public function teamvalideAction($id) {
		//Recovery entity Players
		$em = $this->getDoctrine()->getManager();
		$Team = $em->find('UAMatchBundle:Match\team', $id);

		//Validate the FOSUsers
		$Players = $Team->getPlayers();
		foreach ($Players as $player) {
			$player->getAccount()->setEnabled(true);
		}
		
		//Update Present
		$Team->setPresent(True);
		$em->persist($Team);
		$em->flush();

		return $this->redirect($this->generateUrl('UAMatchAdmin_Team'));
	}

	/**
	 * @Secure(roles="ROLE_ARBITRE")
	 */
	public function teamdeleteAction($id) {
		//Recovery entity Team
		$em = $this->getDoctrine()->getManager();
		$team = $em->find('UAMatchBundle:Match\team', $id);

		//Delete it
		$em->remove($team);
		$em->flush();

		//redirect to playerAdd
		return $this->redirect($this->generateUrl('UAMatchAdmin_Team'));
	}

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function gameAction($page) {
		//Initialize
		$success = false;
		$newGame = new game();
		$em = $this->getDoctrine()->getManager();
		$form = $this->createForm(new gameType, $newGame);

		//Save data
		if ($this->getRequest()->getMethod() == 'POST') {
			$form->bind($this->getRequest());

			if ($form->isValid()) {
				$newGame->gameUpload();

				//Save in Database 
				$em->persist($newGame);
				$em->flush();

				//Create a empty form to add an other game
				$form = $this->createForm(new gameType);
				$success = true;
			}
		}

		//Recovery game
		$games = $em->getRepository('UAMatchBundle:game')->findBy(array(), array('id' => 'ASC'), 10, ($page-1)*10);


		//Display the form
		return $this->render('UAMatchBundle:Admin:game.html.twig', array(	'games' => $games, 
																			'form' => $form->createView(), 
																			'success' => $success));
	}

	/**
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function gamedeleteAction($gameId) {
		//Recovery entity Players
		$em = $this->getDoctrine()->getManager();
		$game = $em->getRepository('UAMatchBundle:game')->find($gameId);

		if(!$game) {
			throw new createNotFoundException('This game doesn\'t exist !');
		}

		//Delete it
		$em->remove($game);
		$em->flush();

		//redirect to playerAdd
		return $this->redirect($this->generateUrl('UAMatchAdmin_Game'));
	}

}
