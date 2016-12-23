# Atlas AI

## Basics

For setup, go down.

This is the GitHub Repository for Project Atlas AI, the smarthome assistant for the UCLA Solar Decathlon 2019 House. The AI is designed to run in WebKit-supported browsers, namely Google Chrome 45+. Project Atlas is listening through the specified microphone for a "trigger phrase," using Google's Speech Recognition API. When the "trigger phrase" is heard, Atlas listens for a short time for a "command." Anything heard during that period of time will be considered part of the command. 

When the period of time listening for the command ends, the string of the command is sent to API.AI for natural language processing, which returns an "intent" and any related information, such as "entities" and "actions." Atlas then selects a connector to the back-end server, depending on the "intent" generated by API.AI. Each connector has it's own means of processing data, though the most basic involves sending the JSON-Object from API.AI directly to the backend, or calling a small "function" on Atlas directly. 

The API of Atlas is a POST-only endpoint. The API will accept JSON-Objects produced by API.AI and extract the intent of the object, similar to the client-side. The API will then, based off the extracted intent, call "API Extensions." These API Extensions execute the required action, based off the JSON-Object. 

The API Extensions often communicate with third-party API's which require authentication. This alone require the extensions to remain on the back-end. Also included in API Extensions are "Language Structures" or natural language responses to certain stimulus, specific to each Extension. 

## Why This is Open-Source

This project has finally been made open-source because I'm tired of managing my own development server. Also because I wanted to try out Codaid which is pretty pointless if I have a development server already. 

Yea yea I know I'm not using branches. I don't care. I'm not deploying anything. I'm not working in a group. I'm testing straight from dev because it's faster. The demo is coming directly from the dev server. If I had a deployment server, I would think about branching. I don't, so I don't branch. I'll think about it.

--- 

# Setup

## The config.ini

You gotta first duplicate the config.example.ini file in "/api/config" to config.ini. You gotta get your API keys and drop them where they need to be. The file is self-documenting. You got this. You don't need documentation. In your Apache (or whatever webserver you use), you should disallow access to the config directly for security. No one needs to see your keys. No one.

## Everything else

Well, in your actual deployment, change the URL in "/assets/scripts/connectors/connectors_server.js" to your deployment URL. I'm too lazy to figure out how to actually set it client side or something. Ain't nobody got time for that. It's one line. 

## Example Apache Config

Here's an example Apache configuration because it seems like everyone gives one. If you use a different web server, you are on your own. You can figure it out. There isn't really much to it.

This example assumes you have a folder called "atlas" with this repo inside of "/var/www/html". If you don't, adapt the configuration to reflect your environment. That's not my job. Also, change the "ServerName" to be your own URL. Please don't use "atlas.example.com" and come to me saying it doesn't work. 

```
<VirtualHost *:80>
    ServerName atlas.example.com
    DocumentRoot /var/www/html

    <Directory /var/www/html>
        Options -Indexes +FollowSymLinks
    </Directory>

    <Directory /var/www/html/atlas/api/config>
        Order deny,allow
        Deny from all
    </Directory>
</VirtualHost>
```