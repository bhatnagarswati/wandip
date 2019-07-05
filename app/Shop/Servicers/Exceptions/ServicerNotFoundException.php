<?php

namespace App\Shop\Servicers\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServicerNotFoundException extends NotFoundHttpException
{

    /**
     * ServicerNotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('Servicer not found.');
    }
}
