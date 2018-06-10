<?php

namespace App\Repositories\TableMeta;

/**
 * Contract for TableMeta Repository.
 *
 * @author Paul Karikari [@koficodes] <paulkarikari1@gmail.com>
 */
interface TableMetaRepositoryInterface
{
    /**
     * Get the table schema of a Devless service.
     *
     * @param integer $service_id
     * @param string $service_name
     * @param string $table_name
     *
     * @return object
     */
    public function getSchema(int $service_id, string $service_name, string $table_name);

    /**
     * Get the table meta of a service.
     *
     * @param integer $service_id
     *
     * @return TableMeta[]
     */
    public function getTableMetaByServiceId(int $service_id);

}
