<?php

namespace App\Security;

use App\Entity\Product;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{

    protected function supports(string $attribute, $subject)
    {
        return 'edit' === $attribute && $subject instanceof Product;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        dump($attribute);
        $user = $token->getUser();

        $product = $subject;

        if ($user === $product->getUser())
        {
            return true;
        }
    }
}