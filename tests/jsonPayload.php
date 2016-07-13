<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [
   'schema' => '{  
     "resource":[  
        {  
           "name":"'.$this->serviceTable.'",
           "description":" demo table",
           "field":[  

              {  
                 "name":"username",
                 "field_type":"text",
                 "default":null,
                 "required":true,
                 "is_unique":true,
                 "ref_table":"",
                  "validation":true
              },
              {  
                 "name":"password",
                 "field_type":"password", 
                 "default":null,
                 "required":true,
                 "is_unique":false,
                 "ref_table":"",
                  "validation":true
              }
           ]
        }
     ]
  }',
    
  'addData' => '{  
   "resource":[  
      {  
         "name":"'.$this->serviceTable.'",
         "field":[  

            {  
               "username":"Edmond",
               "password":"password"
            }
         ]
      }

    ]
}',  
         
    
];