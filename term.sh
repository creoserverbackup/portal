#!/bin/bash

gnome-terminal -- /bin/sh -c 'php artisan websockets:serve --port 6551; exec bash'
gnome-terminal -- /bin/sh -c 'npm run watch; exec bash' #--working-directory=/srv/http/Toflow
gnome-terminal -- /bin/sh -c 'php artisan serv --host laravel.local; exec bash'
