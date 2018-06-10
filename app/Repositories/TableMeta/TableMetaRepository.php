<?php

namespace App\Repositories\TableMeta;

use App\TableMeta;

/**
 * Handles data access layer for Table Metas
 *
 * @author Paul Karikari [@koficodes] <paulkarikari1@gmail.com>
 */
class TableMetaRepository implements TableMetaRepositoryInterface
{
    /**
     * Holds intance of TableMeta Model.
     *
     * @var TableMeta
     */
    private $table_meta;

    /**
     * Create a new intance of TableMetaRepository
     *
     * @param TableMeta $table_meta
     */
    public function __construct(TableMeta $table_meta)
    {
        $this->table_meta = $table_meta;
    }

    /**
     * {@inheritDoc}
     */
    public function getSchema(int $service_id, string $service_name, string $table_name)
    {
        return $this->table_meta
            ->where('table_name', $service_name . '_' . $table_name)
            ->where('service_id', $service_id)
            ->first()->schema;
    }

    /**
     * {@inheritDoc}
     */
    public function getTableMetaByServiceId(int $service_id)
    {
        return $this->table_meta->where('service_id', $service_id)->get();
    }

}
