#!/usr/bin/env bash

rsync -av vendor/elpr/anax-weather-module/config ./

rsync -av vendor/elpr/anax-weather-module/src ./

rsync -av vendor/elpr/anax-weather-module/view ./

rsync -av vendor/elpr/anax-weather-module/test ./

rsync -av vendor/elpr/anax-weather-module/weather ./htdocs/img/