#!/bin/bash
sudo rm -r /home/ronan/Uni/COM682/Project-prod/backend/public/css /home/ronan/Uni/COM682/Project-prod/backend/public/img /home/ronan/Uni/COM682/Project-prod/backend/public/js
sudo cp -r /home/ronan/Uni/COM682/Project/frontend/dist/. /home/ronan/Uni/COM682/Project-prod/backend/public
sudo mv /home/ronan/Uni/COM682/Project-prod/backend/public/index.html /home/ronan/Uni/COM682/Project-prod/backend/resources/views/home.blade.php
sudo cp  /home/ronan/Uni/COM682/Project/backend/public/css/auth.css /home/ronan/Uni/COM682/Project-prod/backend/public/css
sudo cp  /home/ronan/Uni/COM682/Project/backend/public/img/logo_transparent.png /home/ronan/Uni/COM682/Project-prod/backend/public/img

