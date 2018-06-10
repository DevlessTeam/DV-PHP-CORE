<?php

namespace App\Http\Controllers;

use App\Repositories\Service\ServiceRepositoryInterface as Service;
use App\Repositories\TableMeta\TableMetaRepositoryInterface as TableMeta;
use Illuminate\Http\Request;

/**
 * The ApiDocController handles the api requests
 * from the console page of devless instance.
 *
 * @author Edmond Mensah [@eddymens] <kwasiamantin@gmail.com>
 * @author Paul Karikari [@koficodes] <paulkarikari1@gmail.com>
 */
class ApiDocController extends Controller
{
    /**
     * Holds the Service Repository instance.
     *
     * @var Service
     */
    private $service;

    /**
     * Holds the TableMeta Resopitory instance.
     *
     * @var TableMeta
     */
    private $table_meta;

    /**
     * Create a new controller instance.
     *
     * @param Service $service
     * @param TableMeta $table_meta
     *
     * @return void
     */
    public function __construct(Service $service, TableMeta $table_meta)
    {
        $this->service = $service;
        $this->table_meta = $table_meta;
    }

    /**
     * Display list of services.
     *
     * @return Response
     */
    public function index()
    {
        return view('api_docs.index', [
            'services' => $this->service->getAll(),
            'menuName' => 'api_docs',
        ]);
    }

    /**
     *Get schema to generate request payload.
     *
     * @return Response
     */

    public function schema($service_id, $service_name, $table_name)
    {
        $schema = $this->table_meta->getSchema(
            $service_id,
            $service_name,
            $table_name
        );
        return json_decode($schema)->field;
    }

    /**
     * Show the form for editing Table Meta data.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($service_id)
    {
        return $this->table_meta->getTableMetaByServiceId($service_id);
    }
}
