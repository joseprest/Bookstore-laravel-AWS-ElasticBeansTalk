#!/bin/bash

bucket="cdn.manivelle.io"
app_name=$(eb status | sed -n -e "s/  Application name: \(.*\)/\1/p")
git_sha=$(git rev-parse --short HEAD)
current_date=$(date +%Y%m%d-%H%M)
env_name=$(eb status | sed -n -e "s/Environment details for: \(.*\)/\1/p")
version="$env_name-$git_sha-$current_date"

if [ -f ".elasticbeanstalk/build/build.zip" ]; then
    rm -f ".elasticbeanstalk/build/build.zip"
fi

find \
    ./artisan \
    ./composer.json \
    ./composer.lock \
    ./.ebextensions \
    ./.env.elasticbeanstalk \
    ./cron.yaml \
    ./app \
    ./bootstrap \
    ./config \
    ./database \
    ./public \
    ./resources \
    ./vendor \
    ./storage \
    ! -path "*.git*" \
    ! -path "./vendor/panneau/bubbles/.git/*" \
    ! -path "./vendor/panneau/panneau/.git/*" \
    ! -path "./bootstrap/cache/*" \
    ! -path "./public/files/*" \
    ! -path "./resources/assets" \
    ! -path "./storage/app/*" \
    ! -path "./storage/debugbar/*" \
    ! -path "./storage/framework/cache/*" \
    ! -path "./storage/framework/sessions/*" \
    ! -path "./storage/framework/views/*" \
    ! -path "./storage/logs/*" \
    -print | zip ".elasticbeanstalk/build/$version.zip" -@
#   -print

aws s3 cp \
    ".elasticbeanstalk/build/$version.zip" \
    "s3://$bucket/build/" \
    --profile dmp@manivelle

aws elasticbeanstalk create-application-version \
    --application-name $app_name \
    --version-label $version \
    --source-bundle S3Bucket="$bucket",S3Key="build/$version.zip" \
    --profile dmp@manivelle

eb deploy --version $version
