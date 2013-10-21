<?php

namespace UA\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaires
 *
 * @ORM\Table(name="_comments")
 * @ORM\Entity(repositoryClass="UA\BlogBundle\Entity\CommentairesRepository")
 */
class Commentaires
{


	/**
	* @var integer $id
	*
	* @ORM\Column(name="id", type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id;

    /**
    * @ORM\ManyToOne(targetEntity="UA\BlogBundle\Entity\Articles", inversedBy="comments", cascade={"persist"})
    * @ORM\JoinColumn(nullable=false)
    */
    private $article;

	/**
	* @var string $auteur
	* 
	* @ORM\Column(name="auteur", type="string", length=255)
	*/
	private $author;

	/**
	* @var text $contenu
	* 
	* @ORM\Column(name="contenu", type="text")
	*/
	private $content;

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

    /**
     * Set author
     *
     * @param string $author
     * @return Commentaires
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Commentaires
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }
}