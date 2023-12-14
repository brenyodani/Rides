<!--
SPDX-FileCopyrightText: Daniel Brenyo <brenyodani@gmail.com>
SPDX-License-Identifier: CC0-1.0
-->
# Rides

## For Development environment use this setup: 
 https://github.com/juliushaertl/nextcloud-docker-dev .


### To start installing this setup, create a folder and cd into the folder: 

```
cd folder
git clone https://github.com/juliushaertl/nextcloud-docker-dev
cd nextcloud-docker-dev
./bootstrap.sh
```

#### Once the installation is done you can start the Nextcloud container using:

```
docker-compose up nextcloud
```

#### After the installation you can login to your nextcloud on :

nextcloud.local

    - username: admin
    - password: admin


#### To install application copy the project folder into workspace/server/apps 

#### After the project folder is in the apps folder, you can enable the application in Nextcloud


### If your setup is not working or there is some bug with docker, you can always run :
```
docker-compose down -v
```
and then: 
```
docker-compose up
```
In extreme cases if you want to start over, clean everything: 
```
docker system prune --all
```


## XDebug installation and configuration 

#### Xdebug is shipped but disabled by default. It can be turned on by running:
``
./scripts/php-mod-config nextcloud xdebug.mode debug
``

### To use XDebug with docker you need to install one of the browser plugins, there is one for chrome and for firefox:

chrome:
https://chromewebstore.google.com/detail/xdebug-chrome-extension/oiofkammbajfehgpleginfomeppgnglk?pli=1

firefox:
https://addons.mozilla.org/en-US/firefox/addon/xdebug-helper-for-firefox/


### In the browser after the installation you need to give the IDE key to the browser extension.

 IDE key: Select the one you are using your setup with: 
 - VSCODE 
 - PHPSTORM 
 - XDEBUG_ECLIPSE 
 - netbeans 
 - macgdbp


### XDebug's launch.json config file for VsCode:

**launch.json**:
```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Launch currently open script",
            "type": "php",
            "request": "launch",
            "program": "${file}",
            "cwd": "${fileDirname}"
        },
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9003
        },
        {
            "name": "Xdebug for Docker",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html/apps/rides": "${workspaceFolder:rides}",
                "/var/www/html": "${workspaceFolder:rides}"
            }
        },
    ]
}
```

### After configuring the VsCode launch.json, open a terminal and open the docker container

#### To find the ID of the nextcloud container 
``
docker ps  
``
#### After you found the ID of the container 
``
docker exec -it [container_id] /bin/bash
``

## For Xdebug we need to configure the php.ini file

#### Usually it is in ./3rdparty/aws/aws-crt-php
``
cd ./3rdparty/aws/aws-crt-php
``

#### If it is not there, to find the php.ini file

``
find -name php.ini
``

#### After finding the file we need to edit it:

``
extension=modules/awscrt.so
xdebug.mode=debug
xdebug.start_with_request = yes
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_autostart=1
xdebug.remote_host=host.docker.internal
xdebug.remote_port=9003
xdebug.remote_handler=dbgp
xdebug.idekey=VSCODE
``



## Obtaining a Certificate for the application


#### The certificates should be stored in ~/.nextcloud/certificates/ so first create the folder if it does not exist yet:

``
mkdir -p ~/.nextcloud/certificates/
``

#### Then change into the directory:

``
cd ~/.nextcloud/certificates/
``

#### To upload a release on the app store you need 3 files: rides.crt-rides.csr-rides.key stored in nextcloud/certificates/ folder


#### First change the application version number in appinfo/info.xml

#### Publish to github and create a new release 

#### From the folder delete node_modules and git and VsCode related folders 

#### Create a tar.gz file of the app

``
tar -czvf rides.tar.gz rides
``
#### upload the app.tar.gz file to github

#### In the docker container you need to sign the application 

#### change APP_ID to the application ID 

``
openssl dgst -sha512 -sign ~/.nextcloud/certificates/APP_ID.key /path/to/app.tar.gz | openssl base64
``


#### To upload the release go to : https://apps.nextcloud.com/developer/apps/releases/new

#### Paste the tar.gz github link and the certificate





