<?php

namespace App\Service\Admin;

use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class CustomCommentTokenParser extends AbstractTokenParser
{
    private $comments = [];

    public function parse(Token $token)
    {
        $this->comments[] = $token->getValue();

        return null;
    }

    public function getComments(): array
    {
        return $this->comments;
    }

    public function getTag()
    {
        return null;
    }
}
