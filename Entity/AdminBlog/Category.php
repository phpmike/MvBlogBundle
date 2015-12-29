<?php

namespace Mv\BlogBundle\Entity\AdminBlog;

use Mv\BlogBundle\Tools\MvString;
/**
 * Category
 * 
 */
class Category
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $posts;

    /**
     * @var integer
     */
    private $lft;

    /**
     * @var integer
     */
    private $lvl;

    /**
     * @var integer
     */
    private $rgt;

    /**
     * @var integer
     */
    private $root;

    /**
     * @var integer
     */
    private $parent;

    /**
     * @var integer
     */
    private $children;


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
     * @return Category
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
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * 
     * @return string
     */
    public function __toString() {
      return (string)$this->getTitle();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add posts
     *
     * @param \Mv\BlogBundle\Entity\AdminBlog\Post $posts
     * @return Category
     */
    public function addPost(\Mv\BlogBundle\Entity\AdminBlog\Post $posts)
    {
        $this->posts[] = $posts;
        
        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Mv\BlogBundle\Entity\AdminBlog\Post $posts
     */
    public function removePost(\Mv\BlogBundle\Entity\AdminBlog\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return Category
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    
        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     * @return Category
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
    
        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer 
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Category
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    
        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Category
     */
    public function setRoot($root)
    {
        $this->root = $root;
    
        return $this;
    }

    /**
     * Get root
     *
     * @return integer 
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set parent
     *
     * @param \Mv\BlogBundle\Entity\AdminBlog\Category $parent
     * @return Category
     */
    public function setParent(\Mv\BlogBundle\Entity\AdminBlog\Category $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Mv\BlogBundle\Entity\AdminBlog\Category 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Mv\BlogBundle\Entity\AdminBlog\Category $children
     * @return Category
     */
    public function addChildren(\Mv\BlogBundle\Entity\AdminBlog\Category $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \Mv\BlogBundle\Entity\AdminBlog\Category $children
     */
    public function removeChildren(\Mv\BlogBundle\Entity\AdminBlog\Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * 
     * @return array of children ids
     */
    public function getChildrenKeys()
    {
        return array_map(function($entity){ return $entity->getId(); }, $this->getChildren()->toArray());
    }

    /**
     * Render childs with "> " before
     * @return string
     */
    public function getSelectRender(){
        if($this->getParent())
            return sprintf('> %s',(string)$this);
        return (string)$this;
    }
    
    public function getSlug(){
        $parent = '';
        if($this->getParent())
            $parent = $this->getParent()->getSlug() . '-';
        $match = array();
        preg_match_all('#(\b[a-z0-9]{3,}\b)#', strtolower( MvString::withoutAccent($this->getTitle()) ), $match);
        
        if(!isset($match[1]) || !count($match[1]))
            return 'categorie';
        
        return $parent . implode('-', array_slice($match[1], 0, 3));
    }
    
    public function getRoutingParams(){
        return array(   'id'    => $this->getId(),
                        'slug'  => $this->getSlug());
    }
}