<?php

namespace UA\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; 
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Articles
 *
 * @ORM\Table(name="_articles")
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
     * @Assert\NotBlank(message="Le titre ne doit pas être vide !")
	 * @Assert\Length(
     *      min = "5",
     *		minMessage="Le titre doit comporter au moins {{ limit }} caractères !")
	 */
	private $title;

	/**
	 * @var string
	 *
	 * @ORM\ManyToOne(targetEntity="UA\UserBundle\Entity\User")
	 */
	private $author;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="contenu", type="text")
	 * @Assert\NotBlank(message="Le contenu de l'article ne peut être vide !")
	 */
	private $content;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="UA\BlogBundle\Entity\Commentaires", mappedBy="article", cascade={"persist", "remove"})
     */
    private $comments;

    public function __construct() {
        $this->date = new \DateTime();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Articles
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Articles
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

    /**
     * Set slug
     *
     * @param string $slug
     * @return Articles
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
     * Set author
     *
     * @param \UA\UserBundle\Entity\User $author
     * @return Articles
     */
    public function setAuthor(\UA\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return \UA\UserBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add comments
     *
     * @param \UA\BlogBundle\Entity\Commentaires $comments
     * @return Articles
     */
    public function addComments(\UA\BlogBundle\Entity\Commentaires $comments)
    {
        $this->comments[] = $comments;
        $comments->setArticle($this);
        return $this;
    }

    /**
     * Remove comments
     *
     * @param \UA\BlogBundle\Entity\Commentaires $comments
     */
    public function removeComments(\UA\BlogBundle\Entity\Commentaires $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }
}