#!/bin/bash
sudo cp -r /home/ronan/Uni/COM682/Project/frontend/dist/. /home/ronan/Uni/COM682/Project-prod/backend/public
sudo mv /home/ronan/Uni/COM682/Project-prod/backend/public/index.html /home/ronan/Uni/COM682/Project-prod/backend/resources/views/home.blade.php