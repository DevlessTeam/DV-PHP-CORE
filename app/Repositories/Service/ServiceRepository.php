<?php

namespace App\Repositories\Service;

use App\Service;

/**
 * Handles data access for Services.
 *
 * @author Paul Karikari [@koficodes] <paulkarikari1@gmail.com>
 */
class ServiceRepository implements ServiceRepositoryInterface
{
    /**
     * Holds Service model instance.
     *
     * @var Service
     */
    private $service;

    /**
     * Create a new ServiceRepository instance.
     *
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * {@inheritDoc}
     */
    public function getAll()
    {
        return $this->service->orderBy('created_at', 'desc')->get();
    }
}
