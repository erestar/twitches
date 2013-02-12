<?php

namespace Erestar\TwitchesBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Erestar\TwitchesBundle\Entity\StoreItem;
use Erestar\TwitchesBundle\Entity\Client;

class PublicController extends Controller
{
    /**
     * @Route("")
     * @Template()
     */
    public function indexAction()
    {
        $clients = $this->getDoctrine()->getRepository('ErestarTwitchesBundle:Client')->findAll();


        $this->initializeItems();

        $user = $this->get('security.context')->getToken();
        return array('clients'=>$clients);
    }


    protected function initializeItems()
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $this->getDoctrine()->getRepository('ErestarTwitchesBundle:Client')->findAll();

        if (!count($clients)) 
        {
            $client = new Client;
            $client->setRandomId('5ccc4dbk7eskoo4oww8cscg88gck480woogos44w48gk8wko4k');
        }


        $store_items = $this->getDoctrine()->getRepository('ErestarTwitchesBundle:StoreItem')->findAll();

        if (!count($store_items)) 
        {

            $item = new StoreItem();
            $item->setName('dagger');
            $item->setDescription("This standard small dagger has only a modest attack but can be jabbed in rapid succession, and is effective in critical hits such as after a parry or when stabbing in the back.\nWith both slash and thrust attacks this dagger is useful in various situations.");

            $item->setCost(5);
            $item->setQuantity(10);

            $em->persist($item);

            $item = new StoreItem();
            $item->setName('broadsword');
            $item->setDescription("The wide blade of this straight sword emphasizes slicing and has no thrust attack.\nThe horizontal sweeping motion makes this sword effective against multiple enemies.");

            $item->setCost(5);
            $item->setQuantity(10);

            $em->persist($item);

            $item = new StoreItem();
            $item->setName('black knight sword');
            $item->setDescription("Greatsword of the Black Knights who wander Lordran. Used to face chaos demons.\nThe Large motion that puts the weight of the body into the attack reflects the great size of their adversaries long ago.");

            $item->setCost(5);
            $item->setQuantity(10);

            $em->persist($item);
        }

        $em->flush();

    }
}
