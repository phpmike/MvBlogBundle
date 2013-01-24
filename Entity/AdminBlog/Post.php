<?php

namespace Mv\BlogBundle\Entity\AdminBlog;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints AS Assert;

/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mv\BlogBundle\Entity\AdminBlog\PostRepository")
 */
class Post
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
     * @ORM\Column(type="string", length=150)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $accroche;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $article;
    
    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinTable(name="post_category",
     *     joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="RESTRICT")}
     * )
     * @Assert\Count(min=1)
     */
    private $categories;
    
    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
     */
    private $comments;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var datetime $publied
     *
     * @ORM\Column(type="datetime")
     */
    private $publied;


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
     * Set title
     *
     * @param string $title
     * @return Post
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
     * Set accroche
     *
     * @param string $accroche
     * @return Post
     */
    public function setAccroche($accroche)
    {
        $this->accroche = $accroche;
    
        return $this;
    }

    /**
     * Get accroche
     *
     * @return string 
     */
    public function getAccroche()
    {
        return $this->accroche;
    }

    /**
     * Set article
     *
     * @param string $article
     * @return Post
     */
    public function setArticle($article)
    {
        $this->article = $article;
    
        return $this;
    }

    /**
     * Get article
     *
     * @return string 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Post
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Post
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }
    
    /**
     * Add categories
     *
     * @param \Mv\BlogBundle\Entity\AdminBlog\Category $categories
     * @return Post
     */
    public function addCategorie(\Mv\BlogBundle\Entity\AdminBlog\Category $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Mv\BlogBundle\Entity\AdminBlog\Category $categories
     */
    public function removeCategorie(\Mv\BlogBundle\Entity\AdminBlog\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set publied
     *
     * @param \DateTime $publied
     * @return Post
     */
    public function setPublied($publied)
    {
        $this->publied = $publied;
    
        return $this;
    }

    /**
     * Get publied
     *
     * @return \DateTime 
     */
    public function getPublied()
    {
        if(!$this->publied || (int)$this->publied->format('Y') <= 0)
          return new \DateTime('now');
        return $this->publied;
    }

    /**
     * Add comments
     *
     * @param \Mv\BlogBundle\Entity\AdminBlog\Comment $comments
     * @return Post
     */
    public function addComment(\Mv\BlogBundle\Entity\AdminBlog\Comment $comments)
    {
        $this->comments[] = $comments;
    
        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Mv\BlogBundle\Entity\AdminBlog\Comment $comments
     */
    public function removeComment(\Mv\BlogBundle\Entity\AdminBlog\Comment $comments)
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
    
    public function getCommentsPublied(){
        $entities = new ArrayCollection($this->getComments()->toArray());
        return $entities->matching(CommentRepository::getPubliedOrderedCriteria())->toArray();
    }

        public function __toString() {
        return (string)$this->getTitle();
    }
    
    public function getSlug(){
        $match = array();
        preg_match_all('#(\b[a-z0-9]{3,}\b)#', strtolower($this->getTitle()), $match);
        
        if(!isset($match[1]) || !count($match[1]))
            return 'article';
        
        return implode('-', array_slice($match[1], 0, 5));
    }
    
    public function getRoutingParams(){
        $category = $this->getCategories()->get(0);
        
        // We should prefer sub categorie to be as default
        foreach($this->getCategories() AS $object){
            if($object->getParent()){
                $category = $object;
                break;
            }
        }
        
        return array(   'id'        => $this->getId(),
                        'slug'      => $this->getSlug(),
                        'category_id'  => $category->getId(),
                        'category'  => $category->getSlug());
    }
}