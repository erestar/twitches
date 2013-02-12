<?php

namespace Erestar\TwitchesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Entity\AuthCode as BaseAuthCode;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\OAuthServerBundle\Model\ClientInterface;

/**
 * AuthCode
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class AuthCode extends BaseAuthCode
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="Erestar\TwitchesBundle\Entity\User")
     */
    protected $user;


    public function getClient()  
    {  
        return $this->client;  
    }  

    public function setClient(ClientInterface $client)  
    {  
        $this->client = $client;  
    }  

    public function getUser()  
    {  
        return $this->user;  
    }  

    public function setUser(UserInterface $user)  
    {  
        $this->user = $user;  
    }  

}