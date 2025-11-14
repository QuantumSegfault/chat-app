#!/bin/sh

COMPOSE_BASE_FILE="compose.base.yaml"

ENV=$1
COMPOSE_ENV_FILE="compose.${ENV}.yaml"

ENV_VARS_FILE="containers/$1/.env"

# secret files required to be available before allowing compose
SECRETS='
db_password
db_root_password
laravel_app_key
'

if [ -z "$ENV" ]; then
    echo "Usage: $0 {dev|prod} [command...]"
    echo "The container CLI used can be controlled via the CONTAINER_ENGINE env variable."
    exit 1
fi

if [ ! -f "$COMPOSE_BASE_FILE" ]; then
    echo "Error: Compose file '$COMPOSE_BASE_FILE' not found."
    exit 1
fi

if [ ! -f "$COMPOSE_ENV_FILE" ]; then
    echo "Error: Compose file '$COMPOSE_ENV_FILE' not found."
    exit 1
fi

if [ ! -f "$ENV_VARS_FILE" ]; then
    echo "Error: Environment variable file '$ENV_VARS_FILE' not found."
    exit 1
fi

for secret in $SECRETS; do
    secret_full_path="containers/$ENV/secrets/$secret"
    if [ ! -f "$secret_full_path" ]; then
        echo "Error: Secret file '$secret_full_path' not found. Please create it."
        exit 1
    fi
done

if [ -n "$CONTAINER_ENGINE" ]; then
    if ! command -v "$CONTAINER_ENGINE" >/dev/null 2>&1; then
        echo "Could not find the command '${CONTAINER_ENGINE}'. Falling back."
        CONTAINER_ENGINE=""
    fi
fi

if [ -z "$CONTAINER_ENGINE" ]; then
    if command -v podman >/dev/null 2>&1; then
        CONTAINER_ENGINE="podman"
    elif command -v docker >/dev/null 2>&1; then
        CONTAINER_ENGINE="docker"
    else
        echo "Could not find a container engine. Try passing one through the CONTAINER_ENGINE env."
        exit 1
    fi
fi

shift

# in dev, set the users within the container to match the running/host user
if [ -z "$WWW_UID" ] && [ "$ENV" = "dev" ]; then
    export WWW_UID=$(id -u)
    export WWW_GID=$(id -g)
fi

${CONTAINER_ENGINE} compose --env-file "$ENV_VARS_FILE" -f "$COMPOSE_BASE_FILE" -f "$COMPOSE_ENV_FILE" "$@"
