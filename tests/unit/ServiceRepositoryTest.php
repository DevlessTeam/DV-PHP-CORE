<?php
use App\Repositories\Service\ServiceRepositoryInterface as ServiceRepository;

/**
 * Test Service Repository
 *
 * @covers App/Repositories/ServiceRepository
 * @group repository
 *
 * @author Paul Karikari [@koficodes] <paulkarikari1@gmail.com>
 */

class ServiceRepositoryTest extends TestCase
{
    public function testGetAll()
    {
        $services = $this->app->make(ServiceRepository::class);

        //first service was created during test setup
        //second service is created by default after registeration
        $this->assertEquals(2, $services->getAll()->count());
    }
}
