var settings = {
  "async": true,
  "crossDomain": true,
  "url": "http://localhost:8000/api/v1/service/auth/db",
  "method": "DELETE",
  "headers": {
    "content-type": "application/json",
    "cache-control": "no-cache",
    "postman-token": "13dcde8b-a730-33d9-cb3d-107961e88638"
  },
  "processData": false,
  "data": "{  \n   \"resource\":[  \n      {  \n         \"name\":\"testtable\",\n         \"params\":[  \n            {  \n               \"drop\":\"true\"    \n            }\n         ]\n      }\n\n    ]\n}        "
}

$.ajax(settings).done(function (response) {
  console.log(response);
});