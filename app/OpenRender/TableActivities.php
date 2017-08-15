<?php

Class TableActivities {

	public function CRUDview($service, $table) {
		$schema = DB::getSchemaBuilder()->getColumnListing('moon_test');
		return ["fields"=>$schema, "table_name"=>$table, "service_name"=>$service];
	}
}