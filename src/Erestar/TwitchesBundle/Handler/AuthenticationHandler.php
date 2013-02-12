<?php
namespace Erestar\TwitchesBundle\Handler;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

use OAuth2\OAuth2;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

use Symfony\Component\DependencyInjection\ContainerAware;

class AuthenticationHandler 
implements AuthenticationSuccessHandlerInterface,
AuthenticationFailureHandlerInterface
{
    private $router;
    private $oauth2;
    private $clientManager;

    public function __construct(Router $router, Oauth2 $oauth2, ClientManagerInterface $clientManager, $clientId)
    {
        $this->router = $router;
        $this->oauth2 = $oauth2;
        $this->clientManager = $clientManager;
        //TODO move this to a configuration file
        $this->clientId = $clientId;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($request->isXmlHttpRequest()) {
            
            $user = $token->getUser();

            $client = $this->clientManager->findClientByPublicId($this->clientId);

            if (!$client) 
            {
                throw new \Exception('No client registered');
            }

            // $fake_request = new Request();
            // $params = array(
            //     'client_id'=> $this->clientId,
            //     'client_secret'=> $this->clientSecret,
            //     'grant_type'=>'client_credentials',
            //     'response_type'=>'token',
            //     'redirect_uri'=>'http://twitches.local/api'
            //     );

            // $fake_request->initialize($params)  ;

            // $response = $this->oauth2->finishClientAuthorization(true, $user, $fake_request, null);

            $token = $this->oauth2->createAccessToken($client, $user);

            $response = new Response();
            $response->setStatusCode(200);

            $response->setContent(json_encode($token));

            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            // If the user tried to access a protected resource and was forces to login
            // redirect him back to that resource
            if ($targetPath = $request->getSession()->get('_security.target_path')) {
                $url = $targetPath;
            } else {
                $url = $this->router->generate('client_index');
            }

            return new RedirectResponse($url);
        }
    }

    public function onAuthenticationFailure(Request $request, 
        AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest()) {
            // Handle XHR here
            $errors = array();
            $errors[] = $exception->getMessage();


            $response = new Response();
            $response->setStatusCode(401);
            $response->setContent(json_encode($errors));
            $response->headers->set('Content-Type', 'application/json');

            return $response;

        } else {
            // Create a flash message with the authentication error message
            $request->getSession()->setFlash('error', $exception->getMessage());
            $url = $this->router->generate('user_login');

            return new RedirectResponse($url);
        }
    }
}