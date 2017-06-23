    <?php
    /**
     * Created by Devless.
     * Author: eddymens
     * Date Created: 9th of June 2017 08:36:26 PM
     * Service: powerQuery
     * Version: 1.3.0
     */
    use App\Helpers\DataStore as DS;

    //Action method for serviceName
    class powerQuery
    {
        public $serviceName = 'powerQuery';

        /**
         * Converts and executes queries
         * @param $rawQuery 
         * @ACL public
         */
        public function execQuery($rawQuery)
        {
            $executableQuery = '\DB::';
            foreach($rawQuery as $method => $params){
                $stringParams = (is_array($params))?implode('","', $params):$params;
                $stringParams = (strlen($stringParams))? "\"$stringParams\"": $stringParams ;
                $executableQuery .= $method."($stringParams)->";  

            };

            eval('$result = '.str_replace("->;", ";", $executableQuery.";"));
            return $result;
        }


        /**
         * Store queries for later use 
         * @param $name
         * @param $rawQuery
         * @ACL public
         */
        public function addQuery($name, $rawQuery)
        {

             $output =  DS::service($this->serviceName, 'queries')
                ->addData([
                    ['name'=>$name, 'query'=>json_encode($rawQuery)]
                    ]);
             return (array)$output;
        }

        /**
         * Get stored query  
         * @param $name
         * @ACL public
         */
        public function getQuery($name)
        {
            $query =  DS::service($this->serviceName, 'queries')
                ->where('name', $name)->getData()['payload']['results'];
            if(isset($query[0])){
                return $query[0];
            }
        }

        /**
         * Execute stored Queries  
         * @param $name
         * @param $args
         * @ACL public
         */
        public function execStoredQuery($name, $args=[])
        {
            $storedQuery = $this->getQuery($name, $args);

            foreach ($args as  $arg_key => $arg_value) {
               $storedQuery->query = 
                str_replace("$".$arg_key, $arg_value, $storedQuery->query);
            }
            if(isset($storedQuery)){
                return $this->execQuery(json_decode($storedQuery->query));
            }
        }

        /**
         * Execute stored Queries  
         * @param $name
         * @param $args
         * @ACL public
         */
        public function getAllQueries()
        {
            return DS::service($this->serviceName, 'queries')->getData()
            ['payload']['results'];
        }

        /**
         * Edit a stored Query
         * @param $name
         * @param $newQuery
         * @ACL public
         */
        public function editStoredQuery($name, $newQuery)
        {
            return DS::service($this->serviceName, 'queries')
                ->where('name', $name)->update(
                    ['query'=>json_encode($newQuery)]
                    );
        }

        /**
         * This method will execute on service importation
         * @ACL private
         */
        public function __onImport()
        {
            //add code here

        }


        /**
         * This method will execute on service exportation
         * @ACL private
         */
        public function __onDelete()
        {
            //add code here

        }


    }

