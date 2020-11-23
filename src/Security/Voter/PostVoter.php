<?php

namespace App\Security\Voter;

use App\Entity\Posts;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PostVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['POST_EDIT', 'POST_VIEW'])
            && $subject instanceof Posts;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        $post = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'POST_EDIT':
                // logic to determine if the user can EDIT
                return $this->canEdit($post, $user);
                // return true or false
                break;
            case 'POST_VIEW':
                // logic to determine if the user can VIEW
                return true;
                break;
        }

        return false;
    }

    private function canEdit(Posts $post, User $user)
    {
        // this assumes that the Post object has a `getOwner()` method
        return $user->getId() === $post->getUser()->getId();
    }
}
