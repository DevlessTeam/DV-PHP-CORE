<?php

Class TableActivities {

	public function CRUDview($service, $table) {
		$schema = DB::getSchemaBuilder()->getColumnListing($service.'_'.$table);
		return ["fields"=>$schema, "table_name"=>$table, "service_name"=>$service];
	}
}