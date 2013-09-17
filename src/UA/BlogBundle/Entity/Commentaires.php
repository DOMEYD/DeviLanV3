<?php

namespace UA\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaires
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UA\BlogBundle\Entity\CommentairesRepository")
 */
class Commentaires
{

	/**
	* @ORM\ManyToOne(targetEntity="UA\BlogBundle\Entity\Articles")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $article;

	/**
	* @var integer $id
	*
	* @ORM\Column(name="id", type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id;

	/**
	* @var string $auteur
	* 
	* @ORM\Column(name="auteur", type="string", length=255)
	*/
	private $auteur;

	/**
	* @var text $contenu
	* 
	* @ORM\Column(name="contenu", type="text")
	*/
	private $contenu;

	/**
	* @var datetime $date
	* 
	* @ORM\Column(name="date", type="datetime")
	*/
	private $date;

	public function __construct() {
		$this->date = new \Datetime();
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
     * Set auteur
     *
     * @param string $auteur
     * @return Commentaires
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
     * @return Commentaires
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

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Commentaires
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
     * Set article
     *
     * @param \Sdz\BlogBundle\Entity\Article $article
     * @return Commentaires
     */
    public function setArticle(\UA\BlogBundle\Entity\Articles $article)
    {
        $this->article = $article;
    
        return $this;
    }

    /**
     * Get article
     *
     * @return \Sdz\BlogBundle\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }
}