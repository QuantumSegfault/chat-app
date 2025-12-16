# Chat App

A basic real-time chat platform (think Rocket.Chat or Discord) built on Laravel and React.

----

## Containers

This project is built to work exclusively with containerization. Compatible with Docker and Podman.

The dev environment includes a "cli" container to development tools (PHP, Composer, etc.).

Two scripts help interact with this.
* `compose.sh` wraps either `podman compose` or `docker compose` (control engine executable with CONTAINER_ENGINE env var) injecting environment specific compose configuration. Usage: `./compose.sh {dev|prod} ...`.
* `dev-cli` is a shortcut for `./compose.sh dev exec cli bash`

The `laravel` directory is the one mounted into the containers. All container configuration files are inaccessible within the containers.

NOTE: `prod` environment has not been prepared yet. Non-functional.

----

## Setup & run
1. Clone the repo
2. Prepare secrets

    The containers feed secrets from three files, `db_password`, `db_root_password`, and `laravel_app_key`, within `containers/<env>/secrets`. Set appropriate passwords, and create an empty `laravel_app_key`
    ```
    mkdir containers/dev/secrets
    echo -n "secretpassword" > containers/dev/secrets/db_password
    echo -n "othersecretpassword" > containers/dev/secrets/db_root_password
    touch containers/dev/secrets/laravel_app_key
    ```
3. Start the containers: `./compose.sh dev up --build -d`
    
    Some containers (namely `vite`) will fail to start. This is expected.

4. Drop into the dev-cli shell: `./dev-cli`
5. Run `composer install`
6. Run `npm install`
7. Run the DB migrations: `php artisan migrate`
8. While still in the dev-cli, generate a new `laravel_app_key` into a file, exit the `dev-cli`, move it into place, and restart the containers
    ```
    php artisan key:generate --show > laravel_app_key
    exit
    mv laravel/laravel_app_key containers/dev/secrets
    ./compose.sh dev restart
    ```
9. Navigate to http://localhost:8080
----

## Internals

### Core components
* Laravel-powered REST API backend
* MariaDB for persistent storage
* Docker compatible containerization
  * Helper scripts `compose.sh` and `dev-cli` to simplify use

### Roadmap
* Authentication w/ Laravel Sanctum
* SPA front-end built with React and Vite(?)
  * Tailwind CSS
* Real-time communication
  * Redis(?) for transient state
  * Laravel Echo & Reverb
* Flesh out "prod" container config
