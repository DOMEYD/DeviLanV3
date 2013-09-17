<?php

namespace UA\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM; //Objet mapping doctrine
use Symfony\Component\Validator\Constraints as Assert; //Library de validation

/**
 * Articles
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UA\BlogBundle\Entity\ArticlesRepository")
 */
class Articles
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
	 * @var \DateTime
	 *
	 * @ORM\Column(name="date", type="datetime")
	 * @Assert\DateTime()
	 */
	private $date;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="titre", type="string", length=255)
	 * @Assert\Length(
     *      min = "10",
     *		minMessage="Le titre doit comporter au moins {{ limit }} caractères !")
	 */
	private $titre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="auteur", type="string", length=255)
	 * @Assert\Length(
     *      min = "2",
     *		minMessage="L'auteur n'est pas valide ! (mini : {{ limit }} caractères !")
	 */
	private $auteur;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="contenu", type="text")
	 * @Assert\NotBlank()
	 */
	private $contenu;

    public function __construct() {
        $this->date = new \DateTime();
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
	 * Set date
	 *
	 * @param \DateTime $date
	 * @return Articles
	 */
	public function setDate($date)
	{
		$this->date = $date;

		return $this;
	}

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Articles
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     * @return Articles
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    
        return $this;
    }

    /**
     * Get auteur
     *
     * @return string 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Articles
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    
        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }
}
