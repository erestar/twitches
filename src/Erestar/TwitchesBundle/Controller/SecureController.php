<?php

namespace Erestar\TwitchesBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecureController extends Controller
{
    /**
     * @Route("/secure", name="secure_index")
     * @Template()
     */
    public function indexAction()
    {
        return new Response('secure');
        // $user = $this->get('security.context')->getToken()->getUser();

        // return array('user'=>$user);
    }
}
