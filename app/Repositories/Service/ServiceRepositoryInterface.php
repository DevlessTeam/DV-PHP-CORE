<?php

namespace App\Repositories\Service;

/**
 * Contract for service repository.
 *
 * @author Paul Karikari [@koficodes] <paulkarikari1@gmail.com>
 */
interface ServiceRepositoryInterface
{
    /**
     * Gets all services.
     *
     * @return Service[]
     */
    public function getAll();
}
