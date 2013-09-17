<?php

namespace UA\MatchBundle\Entity\Match;

use Doctrine\ORM\Mapping as ORM;

/**
 * team
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UA\MatchBundle\Entity\Match\teamRepository")
 */
class team
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


    /**
     * @ORM\ManyToOne(targetEntity="UA\MatchBundle\Entity\game")
     * @ORM\JoinColumn(nullable=false, onDelete="cascade")
     */
    private $game;

    /**
     * @var boolean
     *
     * @ORM\Column(name="present", type="boolean")
     */
    private $present;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="pool", type="smallint", nullable=true)
     */
    private $pool;


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
     * Set name
     *
     * @param string $name
     * @return team
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set present
     *
     * @param boolean $present
     * @return team
     */
    public function setPresent($present)
    {
        $this->present = $present;
    
        return $this;
    }

    /**
     * Get present
     *
     * @return boolean 
     */
    public function getPresent()
    {
        return $this->present;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return team
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set pool
     *
     * @param integer $pool
     * @return team
     */
    public function setPool($pool)
    {
        $this->pool = $pool;
    
        return $this;
    }

    /**
     * Get pool
     *
     * @return integer 
     */
    public function getPool()
    {
        return $this->pool;
    }

    /**
     * Set game
     *
     * @param \UA\MatchBundle\Entity\game $game
     * @return team
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