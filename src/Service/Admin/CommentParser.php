<?php

namespace App\Service\Admin;

use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;
use Twig\TokenParser\TokenParserInterface;

class CommentParser extends AbstractTokenParser implements TokenParserInterface
{
    private $comments = [];

    public function parse(Token $token)
    {
        // Add the comment to the array
        $this->comments[] = $token->getValue();

        // Skip over the comment
        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);

        // Return an empty output node
        return new \Twig\Node\Node();
    }

    public function getTag()
    {
        return 'comment';
    }

    public function getComments(): array
    {
        return $this->comments;
    }
}
