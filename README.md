# anax-weather-module

[![Build Status](https://travis-ci.org/pererselena/anax-weather-module.svg?branch=master)](https://travis-ci.org/pererselena/anax-weather-module)


Table of content
------------------------------------

* [Install as Anax module](#Install-as-Anax-module)
* [Dependency](#Dependency)
* [License](#License)



Install as Anax module
------------------------------------

This is how you install the module into an existing Anax installation.

Install using composer.

```
composer require elpr/anax-weather-module
```

### Install using scaffold postprocessing file


The module supports a postprocessing installation script, to be used with Anax scaffolding. The script executes the default installation.

```text
bash vendor/elpr/anax-weather-module/.anax/scaffold/postprocess.d/300_weather.bash
```

The postprocessing script should be run after the `composer require` is done.

### Manual installation

Copy the needed configuration and setup the anax-weather-module as a route handler for the route `weather`.

```
rsync -av vendor/elpr/anax-weather-module/config ./
```

```
rsync -av vendor/elpr/anax-weather-module/src ./
```

```
rsync -av vendor/elpr/anax-weather-module/view ./
```

```
rsync -av vendor/elpr/anax-weather-module/test ./
```

```
rsync -av vendor/elpr/anax-weather-module/weather ./htdocs/img/
```

The anax-weather-module is now active on the route `weather/`. According to the API documentation you may try it out on the route `json_weather` to get a json response.

### API keys

Create a new `config/api_keys.php` and store your API keys there. You can see example `config/api_keys_ex.php`.


Dependency
------------------

This is a Anax modulen and primarly intended to be used together with the Anax framework.



License
------------------

This software carries a MIT license. See [LICENSE.txt](LICENSE.txt) for details.



```
 .  
..:  Copyright (c) 2019 Elena Perers
```