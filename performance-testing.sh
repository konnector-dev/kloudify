#!/bin/bash
echo 'Performance testing...'

# newman run ./API-v1-qa.postman_collection.json -e ./UAT.postman_environment.json 
#echo '2'
#newman run ./API-v1-qa.postman_collection.json -e ./UAT.postman_environment.json  > /dev/null 2>&1 < /dev/null &
#echo '3'
#newman run ./API-v1-qa.postman_collection.json -e ./UAT.postman_environment.json  > /dev/null 2>&1 < /dev/null &

LOADTEST='newman run ./API-v1-qa.postman_collection.json -e ./UAT.postman_environment.json'

for i in {1..10}
do
 LOADTEST="${LOADTEST} & newman run ./API-v1-qa.postman_collection.json -e ./UAT.postman_environment.json"
done
eval $LOADTEST