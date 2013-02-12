<?php
namespace Erestar\TwitchesBundle\Controller;  
  
use Symfony\Bundle\FrameworkBundle\Controller\Controller;  
use Symfony\Component\HttpFoundation\Request;  
use Symfony\Component\HttpFoundation\Response;  
use Symfony\Component\Security\Core\SecurityContext;  
  
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecurityController extends Controller  
{  

    public function loginAction(Request $request)  
    {  
        $session = $request->getSession();  
          
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {  
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);  
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {  
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);  
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);  
        } else {  
            $error = '';  
        }  
  
        if ($error) {  
            $error = $error->getMessage(); // WARNING! Symfony source code identifies this line as a potential security threat.  
        }  
          
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);  

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
  
        return $this->render('ErestarTwitchesBundle:Security:login.html.twig', array(  
            'last_username' => $lastUsername,  
            'csrf_token' => $csrfToken,
            'error'         => $error,  
        ));  
    }  
      
    public function loginCheckAction(Request $request)  
    {  

        return new Response('I should not be here');
          
    }  
}  