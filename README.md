# Stalingrad Showdown

## Setup locally

To setup the project clone the repository:

    git clone https://github.com/ricardogomez-dev/stalingrad-showdown.git

Then enter to the project:

    cd stalingrad-showdown

Build the project docker container:

    docker compose up --build

After building the container, install the composer dependencies, you can open a new terminal window and make sure to enter to the project stalingrad-showdown:

    docker-compose exec php composer install

Run the database seeder:

    docker compose exec php php seeders/DatabaseSeeder.php

Then go to the frontend folder:

    cd frontend

Then install npm dependencies:

    npm install

Then build the frontend:

    npm run dev

By last you need to run tailwindcss watcher:

    npx tailwindcss -i ./src/input.css -o ./src/output.css --watch

You are all set with the project! 🥳
