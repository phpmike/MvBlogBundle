<?php

namespace Mv\BlogBundle\Entity\AdminBlog;

use Symfony\Component\Validator\Constraints AS Assert;
use Mv\BlogBundle\Validator\Constraints AS MvAssert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Comment
 * 
 * @Assert\Callback(methods={"validateIpControl"})
 */
class Comment
{
    /**
     * @var integer
     */
    private $id;
    
    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(min= 4, max= 50)
     */
    private $pseudo;
    
    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Email(checkHost = true)
     * @MvAssert\NoTmpMail
     */
    private $email;
    
    /**
     * @var string
     *
     * @Assert\Url
     */
    private $web;
    
    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Ip(version="all")
     * @MvAssert\ClientIp(groups="ip_control_group")
     */
    private $ip;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(min= 10, max= 1000)
     */
    private $comment;
    
    /**
     * @var
     */
    private $post;

    /**
     * @var datetime $created
     */
    private $created;

    /**
     * @var datetime $publied
     */
    private $publied;
    
    /**
     * @var string
     */
    private $token;
    
    /**
     * @var datetime
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
    
    public function validateIpControl(ExecutionContextInterface $ec){
        if(!$this->id) 
        {
            $ec->validate($this, 'ip_control_group', $ec->getPropertyPath(), true);
        }
    }
}