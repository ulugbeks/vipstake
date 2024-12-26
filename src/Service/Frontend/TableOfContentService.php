<?php

namespace App\Service\Frontend;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\DomCrawler\Crawler;

class TableOfContentService
{
    private array $links = [];

    public function __construct(private $content)
    {
    }

    public function create(): self
    {
        if ($this->content) {
            $crawler = new Crawler();
            $crawler->addContent($this->content);

            $crawler->filter('h2, h3, h4, h5, h6')->each(function ($node) {
                /** @var Crawler $node */
//                $slug = Urlizer::urlize(Urlizer::transliterate($node->text()));
//                $node->getNode(0)->setAttribute('id', $slug);
                $this->links[] = [
                    'id' => $node->getNode(0)->getAttribute('id'),
                    'text' => $node->text(),
                ];
            });


            $this->content = $crawler->filter('body')->html();
        } else {
            $this->links = [];
            $this->content = '';
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }
}