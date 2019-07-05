<?php

namespace App\v1\Provinces\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProvinceNotFoundException extends NotFoundHttpException
{
    /**
     * ProvinceNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('Province not found.');
    }
}
