<?php
namespace BehatViewer\BehatViewerApiBundle\Security;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use BehatViewer\BehatViewerApiBundle\Security\UserToken;

class UserFirewall implements ListenerInterface
{
	protected $securityContext;
	protected $authenticationManager;

	public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager)
	{
		$this->securityContext = $securityContext;
		$this->authenticationManager = $authenticationManager;
	}

	public function handle(GetResponseEvent $event)
	{
		$request = $event->getRequest();

		if ($request->server->has('PHP_AUTH_USER') && $request->server->has('PHP_AUTH_PW')) {
			$token = new UserToken();
			$token->setUser($request->server->get('PHP_AUTH_USER'));
			$token->token   = $request->server->get('PHP_AUTH_PW');

			try {
				$returnValue = $this->authenticationManager->authenticate($token);

				if ($returnValue instanceof TokenInterface) {
					return $this->securityContext->setToken($returnValue);
				} elseif ($returnValue instanceof Response) {
					$event->setResponse($returnValue);
				}
			} catch (AuthenticationException $e) {
			}
		}

		$response = new Response();
		$response->setStatusCode(403);
		$event->setResponse($response);
	}
}