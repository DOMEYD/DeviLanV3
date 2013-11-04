<?php

namespace UA\MatchBundle\Entity\Match;

use Doctrine\ORM\Mapping as ORM;

/**
 * matchs
 *
 * @ORM\Table(name="_matchs")
 * @ORM\Entity(repositoryClass="UA\MatchBundle\Entity\Match\matchsRepository")
 */
class matchs
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="UA\MatchBundle\Entity\Match\team")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teamA;

    /**
     * @ORM\ManyToOne(targetEntity="UA\MatchBundle\Entity\Match\team")
     * @ORM\JoinColumn(nullable=true)
     */
    private $teamB;

    /**
     * @var integer
     *
     * @ORM\Column(name="scoreA", type="smallint", nullable=true)
     */
    private $scoreA;

    /**
     * @var integer
     *
     * @ORM\Column(name="scoreB", type="smallint", nullable=true)
     */
    private $scoreB;

    /**
     * @var string
     *
     * @ORM\Column(name="step", type="string", length=255)
     */
    private $step;

    /**
     * @ORM\ManyToOne(targetEntity="UA\MatchBundle\Entity\game", inversedBy="matchs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="horaire", type="datetime", nullable=true)
     */
    private $horaire;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPool() {
        return $this->teamA->getPool();
    }


    /**
     * Set scoreA
     *
     * @param integer $scoreA
     * @return matchs
     */
    public function setScoreA($scoreA)
    {
        $this->scoreA = $scoreA;
    
        return $this;
    }

    /**
     * Get scoreA
     *
     * @return integer 
     */
    public function getScoreA()
    {
        return $this->scoreA;
    }

    /**
     * Set scoreB
     *
     * @param integer $scoreB
     * @return matchs
     */
    public function setScoreB($scoreB)
    {
        $this->scoreB = $scoreB;
    
        return $this;
    }

    /**
     * Get scoreB
     *
     * @return integer 
     */
    public function getScoreB()
    {
        return $this->scoreB;
    }

    /**
     * Set step
     *
     * @param string $step
     * @return matchs
     */
    public function setStep($step)
    {
        $this->step = $step;
    
        return $this;
    }

    /**
     * Get step
     *
     * @return string 
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * Set horaire
     *
     * @param \DateTime $horaire
     * @return matchs
     */
    public function setHoraire($horaire)
    {
        $this->horaire = $horaire;
    
        return $this;
    }

    /**
     * Get horaire
     *
     * @return \DateTime 
     */
    public function getHoraire()
    {
        return $this->horaire;
    }

    /**
     * Set teamA
     *
     * @param \UA\MatchBundle\Entity\Match\team $teamA
     * @return matchs
     */
    public function setTeamA(\UA\MatchBundle\Entity\Match\team $teamA)
    {
        $this->teamA = $teamA;
    
        return $this;
    }

    /**
     * Get teamA
     *
     * @return \UA\MatchBundle\Entity\Match\team 
     */
    public function getTeamA()
    {
        return $this->teamA;
    }

    /**
     * Set teamB
     *
     * @param \UA\MatchBundle\Entity\Match\team $teamB
     * @return matchs
     */
    public function setTeamB(\UA\MatchBundle\Entity\Match\team $teamB)
    {
        $this->teamB = $teamB;
    
        return $this;
    }

    /**
     * Get teamB
     *
     * @return \UA\MatchBundle\Entity\Match\team 
     */
    public function getTeamB()
    {
        return $this->teamB;
    }

    /**
     * Set game
     *
     * @param \UA\MatchBundle\Entity\game $game
     * @return matchs
     */
    public function setGame(\UA\MatchBundle\Entity\game $game)
    {
        $this->game = $game;
    
        return $this;
    }

    /**
     * Get game
     *
     * @return \UA\MatchBundle\Entity\game 
     */
    public function getGame()
    {
        return $this->game;
    }
}