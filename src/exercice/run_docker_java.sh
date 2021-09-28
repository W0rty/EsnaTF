#!/bin/bash

cp users/$1 ../script_to_run_docker/java/
rm users/$1
cd ../script_to_run_docker/java/"$2"
random_file_test=$(tr -dc A-Za-z </dev/urandom | head -c 13 ; echo '') 
ext=".java"
random_file_name=$(echo $random_file_test$ext)
cp test.java $random_file_name
sed -i "s/test/$random_file_test/" $random_file_name
sleep 0.1
sed -i "s/Vehicule/$3/g" $random_file_name
cd ..
docker build -q --build-arg user_script_name="$1" --build-arg user_script_name_without_java="$3" --build-arg exo_name="$2" --build-arg test_script_name="$random_file_name" --build-arg test_script_name_without_java="$random_file_test" . -t $4 >/dev/null
res=$(docker run --rm --name $4 $4)
rm "$2"/$random_file_name
rm $1
docker image rm $4 >/dev/null
echo $res
