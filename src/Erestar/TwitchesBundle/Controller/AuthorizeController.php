<?php
namespace Erestar\TwitchesBundle\Controller;  
  
use Symfony\Component\Security\Core\SecurityContext;  
use Symfony\Component\HttpFoundation\Request;  
use FOS\OAuthServerBundle\Controller\AuthorizeController as BaseAuthorizeController;  
use Erestar\TwitchesBundle\Form\Model\Authorize;  
use Erestar\TwitchesBundle\Entity\Client;  
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;  
  
class AuthorizeController extends BaseAuthorizeController  
{  
    
    public function authorizeAction(Request $request)  
    {  
        if (!$request->get('client_id')) {  
            throw new NotFoundHttpException("Client id parameter {$request->get('client_id')} is missing.");  
        }  
          
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');  
        $client_id = $request->get('client_id');
        $client = $clientManager->findClientByPublicId($request->get('client_id'));  

        if (!($client instanceof Client)) {  
            throw new NotFoundHttpException("Client {$request->get('client_id')} is not found.");  
        }  
          
        $user = $this->container->get('security.context')->getToken()->getUser();  

        $form = $this->container->get('erestar_twitches.authorize.form');  
        $formHandler = $this->container->get('erestar_twitches.authorize.form_handler');  
          
        $authorize = new Authorize();  
          
        if (($response = $formHandler->process($authorize)) !== false) {  
            return $response;  
        }  

        $response_type = $request->get('response_type');
        if ($response_type == 'authorization_code') 
        {
            $response_type = 'code';
        }
        $redirect_uri = $request->get('redirect_uri');
        $state = '';
        $scope = '';
                  
        return $this->container->get('templating')->renderResponse('ErestarTwitchesBundle:Authorize:authorize.html.twig', array(  
            'form' => $form->createView(),  
            'response_type'=>$response_type,
            'redirect_uri'=>$redirect_uri,
            'state'=>$state,
            'scope'=>$scope,
            'client_id' => $client_id,  
            'client'=>$client
        ));  
    }  
    
}  