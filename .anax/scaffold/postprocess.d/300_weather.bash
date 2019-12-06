#!/usr/bin/env bash

rsync -av vendor/anax/anax-weather-module/config ./

rsync -av vendor/anax/anax-weather-module/src ./

rsync -av vendor/anax/anax-weather-module/view ./

rsync -av vendor/anax/anax-weather-module/test ./

rsync -av vendor/anax/anax-weather-module/weather ./htdocs/img/