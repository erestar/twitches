<?php

namespace Erestar\TwitchesBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Erestar\TwitchesBundle\Entity\Client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @Route("/client")
 */

class ClientController extends Controller
{
    /**
     * @Route("", name="client_index")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $client = $this->getDoctrine()->getRepository('ErestarTwitchesBundle:Client')->findOneByName('JavaScript Client');

        if (!($client instanceOf Client)) 
        {
            throw new \Exception('No client named "JavaScript Client" has been registered');
        }

        return array('client_id' => $client->getPublicId());
    }

    /**
     * @Route("/refresh", name="client_refresh")
     * @Template()
     */
    public function refreshAction(Request $request)
    {
        $securityContext = $this->container->get('security.context');
        $securityToken = $securityContext->getToken();

        $auth_handler = $this->container->get('erestar.twitches.authentication_handler');
        if( $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
            return $auth_handler->onAuthenticationSuccess($request, $securityToken);
        } else {
            $exception = new AuthenticationException('Session not logged in. Cannot grant an access token.');
            return $auth_handler->onAuthenticationFailure($request, $exception);
        }
    }

    /**
     * @Route("/force", name="client_force_login")
     * @Template()
     */
    public function forceAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $oauth2 = $this->get('fos_oauth_server.server');

        $fake_request = new Request();
        $params = array(
            'client_id'=>'5_25cckgiuub404s0ksgwswgsw8ckw00wocogogc8w804k8w0wco',
            'client_secret'=>'449bm2t4ham80sswgccgowoossswoocowoscwsogkc8gog4gos',
            'grant_type'=>'client_credentials',
            'response_type'=>'token',
            'redirect_uri'=>'/api/'
            );
        $fake_request->initialize($params);

        $oauth2->finishClientAuthorization(true, $user, $fake_request, null);


        return $this->redirect($this->generateUrl('client_index'));

    }
}
