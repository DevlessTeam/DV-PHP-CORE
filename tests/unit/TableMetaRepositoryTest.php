<?php
use App\Repositories\TableMeta\TableMetaRepositoryInterface as TableMetaRepository;
use App\TableMeta;

/**
 * Test TableMeta Repository
 *
 * @covers App/Repositories/TableMeta
 * @group repository
 *
 * @author Paul Karikari [@koficodes] <paulkarikari1@gmail.com>
 */
class TableMetaRepositoryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->table_meta = $this->app->make(TableMetaRepository::class);
        (new TableMeta)->forceCreate($this->inputForCreateTableMeta());

    }

    public function testGetSchema()
    {

        $schema = $this->table_meta->getSchema(1, 'devless', 'user_profile');

        $this->assertEquals(json_decode($schema)->name, 'user_profile');
    }

    public function testGetTableMetaByServiceId()
    {
        $table_meta = $this->table_meta->getTableMetaByServiceId(1);

        $this->assertEquals($table_meta->count(), 1);
        $this->assertEquals($table_meta->first()->table_name, 'devless_user_profile');

    }

    public function inputForCreateTableMeta()
    {
        return [
            'table_name' => 'devless_user_profile',
            'schema' => json_encode([
                "name" => "user_profile",
                "description" => "Table for storing DevLess extended profiles",
                "field" => '{
                            "name" : "users_id",
                            "field_type" : "reference",
                            "ref_table" : "_devless_users",
                            "default" : null,
                            "required" : false,
                            "validation" : false,
                            "is_unique" : true }',
            ]),
            'service_id' => 1,
        ];
    }
}
