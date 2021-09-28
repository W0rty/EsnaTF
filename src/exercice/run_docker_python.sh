#!/bin/bash

cp users/$1 ../script_to_run_docker/python/
rm users/$1
cd ../script_to_run_docker/python/

docker build -q --build-arg user_script_name="$1" . -t $2 >/dev/null
res=$(docker run --rm --name $2 $2)
rm $1
docker image rm $2 >/dev/null
echo $res
