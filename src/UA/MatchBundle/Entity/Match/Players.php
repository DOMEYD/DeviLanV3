<?php

namespace UA\MatchBundle\Entity\Match;

use Doctrine\ORM\Mapping as ORM;

/**
 * Players
 *
 * @ORM\Table(name="_players")
 * @ORM\Entity(repositoryClass="UA\MatchBundle\Entity\Match\PlayersRepository")
 */
class Players
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
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @ORM\ManyToOne(targetEntity="UA\MatchBundle\Entity\Match\team", inversedBy="players")
     * @ORM\JoinColumn(nullable=false, onDelete="cascade")
     */
    private $team;

    /**
     * @ORM\OneToOne(targetEntity="UA\UserBundle\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Players
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set team
     *
     * @param \UA\MatchBundle\Entity\Match\team $team
     * @return Players
     */
    public function setTeam(\UA\MatchBundle\Entity\Match\team $team)
    {
        $this->team = $team;
    
        return $this;
    }

    /**
     * Get team
     *
     * @return \UA\MatchBundle\Entity\Match\team 
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set account
     *
     * @param \UA\UserBundle\Entity\User $account
     * @return Players
     */
    public function setAccount(\UA\UserBundle\Entity\User $account)
    {
        $this->account = $account;
    
        return $this;
    }

    /**
     * Get account
     *
     * @return \UA\UserBundle\Entity\User 
     */
    public function getAccount()
    {
        return $this->account;
    }
}