#!/bin/bash

cp users/$1 ../script_to_run_docker/c/
rm users/$1
cd ../script_to_run_docker/c/

docker build -q --build-arg user_script_name="$1" --build-arg exercice="$2" . -t $3 >/dev/null
res=$(docker run --rm --name $3 $3)
rm $1
docker image rm $3 >/dev/null
if [[ $res == *"FAIL"* ]]; then
    echo "Votre script n'a pas marche, veuillez verifier votre script."
    exit
fi

if [[ $res == *"Compilation failed or warnings detected"* ]]; then
    echo "Une erreur de compilation est survenue, veuillez verifier votre script."
    exit
fi

if [[ $res == *"All tests passed, congratulations !"* ]]; then
    echo "All tests passed, congratulations !"
    exit
fi

echo "Une erreur innatendue est survenue, veuillez appeler iHuggsy"
exit
