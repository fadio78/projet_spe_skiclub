<?php
//src/SC/UserBundle/Security/Firewall/ExceptionListener.php
namespace SC\UserBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Firewall\ExceptionListener as BaseExceptionListener;

class ExceptionListener extends BaseExceptionListener
{
    protected function setTargetPath(Request $request)
    {
        // Ne conservez pas de chemin cible pour les requêtes XHR et non-GET
        // Vous pouvez ajouter n'importe quelle logique supplémentaire ici
        // si vous le voulez
        
        $session = $request->getSession();
        $usr= $this->get('security.context')->getToken()->getUser();
        $email = $usr->getUsername();
        $type = $usr->getType();
        $session->set('email',$email );
        $session->set('type',$type );
        
        if ($request->isXmlHttpRequest() || 'GET' !== $request->getMethod()) {
            return;
        }
        
                //$request->getUri()
        $request->getSession()->set('_security.main.target_path', $this->generateUrl('sc_activite_homepage') );
    }
}