<?php
namespace Erestar\TwitchesBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/api")
 */

class ApiController extends FOSRestController
{
    /**
     * @Route("/reflect", name="api_reflect")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();


        $view = $this->view($user, 200);
        return $this->handleView($view);
    }

    /**
     * @Route("/items", name="api_store_items")
     * @Method("GET")
     * @Template()
     */
    public function getStoreItemsAction()
    {
        $store_items = $this->getDoctrine()->getRepository('ErestarTwitchesBundle:StoreItem')->findAll();

        $view = $this->view($store_items, 200);
        return $this->handleView($view);
    }


}
