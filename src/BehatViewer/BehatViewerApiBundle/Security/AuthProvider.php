<?php
namespace BehatViewer\BehatViewerApiBundle\Security;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AuthProvider implements AuthenticationProviderInterface
{
    private $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        if ($this->validateDigest($user, $token->token)) {
            $authenticatedToken = new UserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The API authentication failed.');
    }

    protected function validateDigest($user, $token)
    {
        return ($user !== null && $user->getToken() === $token);
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof UserToken;
    }
}
