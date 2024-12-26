<?php

namespace App\Interface;

use App\Entity\Admin\FieldsGroup;

interface FieldsBuilderInterface
{
    const BASE_PATH = "meta";

    public function create(FieldsGroup $group, $data = []);

}