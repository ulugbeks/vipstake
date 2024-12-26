<?php

namespace App\Trait\Frontend;

use App\Entity\Frontend\City;
use App\Entity\Frontend\Country;

trait FindIdsTrait
{
    public function findIds()
    {
        return $this->createQueryBuilder('p')
            ->select('p.id')
            ->getQuery()
            ->getResult();
    }
}