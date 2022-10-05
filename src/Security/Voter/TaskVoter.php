<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends Voter
{
    public const DELETE = 'DELETE';
    public const EDIT = 'EDIT';
    public const TOGGLE = 'TOGGLE';

    public function __construct(private Security $security) {}

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::DELETE, self::EDIT, self::TOGGLE])
            && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        
        if (!$user instanceof UserInterface) {
            return false;
        }
        
        switch ($attribute) {
            case self::DELETE:
                if ($this->security->isGranted('ROLE_ADMIN', $user) && $subject->getUser() === null) {
                    return true;
                }
                return $this->isValid($subject, $user);
            case self::EDIT:
                return $this->isValid($subject, $user);
            case self::TOGGLE:
                return $this->isValid($subject, $user);
        }

        return false;
    }

    private function isValid(Task $subject, User $user)
    {
        if ($subject->getUser()->getId() === $user->getId()) {
            return true;
        }
        
        return false;
    }
}
