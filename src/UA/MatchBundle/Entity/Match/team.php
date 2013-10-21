<?php

namespace UA\MatchBundle\Entity\Match;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * team
 *
 * @ORM\Table(name="_teams")
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
     * @ORM\ManyToOne(targetEntity="UA\MatchBundle\Entity\game", inversedBy="teams")
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
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="UA\MatchBundle\Entity\Match\Players", mappedBy="team", cascade={"persist", "remove"})
     */
    private $players;

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

    /**
     * Set slug
     *
     * @param string $slug
     * @return team
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->players = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add players
     *
     * @param \UA\MatchBundle\Entity\Match\Players $players
     * @return team
     */
    public function addPlayer(\UA\MatchBundle\Entity\Match\Players $players)
    {
        $this->players[] = $players;
    
        return $this;
    }

    /**
     * Remove players
     *
     * @param \UA\MatchBundle\Entity\Match\Players $players
     */
    public function removePlayer(\UA\MatchBundle\Entity\Match\Players $players)
    {
        $this->players->removeElement($players);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayers()
    {
        return $this->players;
    }
}