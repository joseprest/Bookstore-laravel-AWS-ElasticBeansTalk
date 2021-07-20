#!/bin/bash

env_name=$1
env=$(echo $env_name | sed -E 's/([a-z]+).*/\1/g')
envfile=${BASH_SOURCE%/*}/.env.$env
envvars=$(cat $envfile | sed '/^\s*$/d' | awk '{print}' ORS=',')
envvars=${envvars%?}

arguments="";
if [[ "$env" != "prod" ]]; then
    arguments="--single"
fi

eb create manivelle-$env_name $arguments --cname manivelle-$env_name --envvars $envvars --cfg manivelle-$env
