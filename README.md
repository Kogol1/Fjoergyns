# Fjoergyns

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)]()
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Application connecting Discord and data from Minecraft server database

## Models and database connections

I am creating model for every plugin with own database connection. 

## Sendings Discord messages

Use custom artisan commands for planned recurrent messages.

## Discord functions so far
<li>Announcing weekly top voters + lottery winner</li>
<li>Announcing monthly top voters</li>
<li>Display warn and ban stats for every member of admin team</li>

##Integration with own performance monitoring app
We have also created android app for monitoring online players count and tps for every Minecraft server we have.
There are several posts and get endpoints for the app. We are returning data in json object and then work with data later in the app. 
