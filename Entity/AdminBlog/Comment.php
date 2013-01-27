<?php

namespace Mv\BlogBundle\Entity\AdminBlog;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints AS Assert;
use Mv\BlogBundle\Validator\Constraints AS MvAssert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mv\BlogBundle\Entity\AdminBlog\CommentRepository")
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 * @Assert\Callback(methods={"validateIpControl"})
 */
class Comment
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
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Assert\MinLength(4)
     */
    private $pseudo;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank
     * @Assert\Email(checkHost = true)
     * @MvAssert\NoTmpMail
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Url
     */
    private $web;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=40)
     * @Assert\NotBlank
     * @Assert\Ip(version="all")
     * @MvAssert\ClientIp(groups="ip_control_group")
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\MinLength(10)
     */
    private $comment;
    
    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="cascade")
     */
    private $post;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var datetime $publied
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publied;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $token;
    
    /**
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    private $deleted;


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
     * Set pseudo
     *
     * @param string $pseudo
     * @return Comment
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    
        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string 
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Comment
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set web
     *
     * @param string $web
     * @return Comment
     */
    public function setWeb($web)
    {
        $this->web = $web;
    
        return $this;
    }

    /**
     * Get web
     *
     * @return string 
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Comment
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Comment
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
     * Set publied
     *
     * @param \DateTime $publied
     * @return Comment
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
        return $this->publied;
    }

    /**
     * Set post
     *
     * @param \Mv\BlogBundle\Entity\AdminBlog\Post $post
     * @return Comment
     */
    public function setPost(\Mv\BlogBundle\Entity\AdminBlog\Post $post = null)
    {
        $this->post = $post;
    
        return $this;
    }

    /**
     * Get post
     *
     * @return \Mv\BlogBundle\Entity\AdminBlog\Post 
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Comment
     */
    public function setToken($token)
    {
        $this->token = $token;
    
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set deleted
     *
     * @param \DateTime $deleted
     * @return Comment
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    
        return $this;
    }

    /**
     * Get deleted
     *
     * @return \DateTime 
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
    
    public function validateIpControl(ExecutionContext $ec){
        if(!$this->id) 
        {
            $ec->getGraphWalker()->walkReference($this, 'ip_control_group', $ec->getPropertyPath(), true);
        }
    }
}