<?php

namespace UA\MatchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; //Library of validation
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * game
 *
 * @ORM\Table(name="_games")
 * @ORM\Entity(repositoryClass="UA\MatchBundle\Entity\gameRepository")
 * @UniqueEntity(fields="name", message="Le nom du jeu ne peut pas être le même qu'une saisie précédente.")
 */
class game
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="shortName", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "1",
     *      max = "4",
     *      minMessage = "Votre abbreviation doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre abbreviation ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $shortName;

    /**
     * @var string
     * @ORM\Column(name="pictures", type="string", length=255)
     */
    private $pictures;

    /**
     * @var string
     * 
     * @ORM\Column(name="generator", type="string", length=255)
     */
    private $generator;

    /**
     * @var integer
     * @ORM\Column(name="nbrPlayerRequired", type="smallint")
     * @Assert\GreaterThan(value=0)
     */
    private $nbrPlayerRequired;

    private $file;

    /**
     * @ORM\OneToMany(targetEntity="UA\MatchBundle\Entity\Match\team", mappedBy="game")
     */
    private $teams;

    public function setFile($file){
        $this->file = $file;
    
        return $this;
    }
    public function getFile(){
        return $this->file;
    }

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
     * @return game
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
     * Set shortName
     *
     * @param string $shortName
     * @return game
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    
        return $this;
    }

    /**
     * Get shortName
     *
     * @return string 
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set pictures
     *
     * @param string $pictures
     * @return game
     */
    public function setPictures($pictures)
    {
        $this->pictures = $pictures;
    
        return $this;
    }

    /**
     * Get pictures
     *
     * @return string 
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Set generator
     *
     * @param string $generator
     * @return game
     */
    public function setGenerator($generator)
    {
        $this->generator = $generator;
    
        return $this;
    }

    /**
     * Get generator
     *
     * @return string 
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * Set nbrPlayerRequired
     *
     * @param integer $nbrPlayerRequired
     * @return game
     */
    public function setNbrPlayerRequired($nbrPlayerRequired)
    {
        $this->nbrPlayerRequired = $nbrPlayerRequired;
    
        return $this;
    }

    /**
     * Get nbrPlayerRequired
     *
     * @return integer 
     */
    public function getNbrPlayerRequired()
    {
        return $this->nbrPlayerRequired;
    }

    /**
     * Fonction - Picture upload
     */
    public function gameUpload() {
        /* If no file prvided */
        if (null === $this->file) {
            return;
        }
        
        /* File name recovery */
        $name = $this->file->getClientOriginalName();
 
        /* Move file in upload folder*/
        $this->file->move(__DIR__.'/../../../../web/uploads/img', $name);

        $this->pictures = $name;
  }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->teams = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add teams
     *
     * @param \UA\MatchBundle\Entity\Match\team $teams
     * @return game
     */
    public function addTeam(\UA\MatchBundle\Entity\Match\team $teams)
    {
        $this->teams[] = $teams;
    
        return $this;
    }

    /**
     * Remove teams
     *
     * @param \UA\MatchBundle\Entity\Match\team $teams
     */
    public function removeTeam(\UA\MatchBundle\Entity\Match\team $teams)
    {
        $this->teams->removeElement($teams);
    }

    /**
     * Get teams
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTeams()
    {
        return $this->teams;
    }
}