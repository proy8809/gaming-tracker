# Define the path to your specific .env file
ENV_FILE=.env.docker

.PHONY: up down restart status logs clean debug

# Default action when you just type 'make'
all: up

# Start the containers
up:
	@echo "Starting containers with environment file: $(ENV_FILE)..."
	docker compose --env-file $(ENV_FILE) up -d

# Stop the containers
down:
	@echo "Stopping containers..."
	docker compose --env-file $(ENV_FILE) down

# Run Tailwind compiler inside the container
tailwind:
	@echo "Starting Tailwind compilation inside $(SERVICE_NAME) container..."
	docker compose exec $(SERVICE_NAME) php bin/console tailwind:build

# Restart the containers
restart: down up

# View current status of the containers
status:
	docker compose --env-file $(ENV_FILE) ps

# View live container logs
logs:
	docker compose --env-file $(ENV_FILE) logs -f

# WARNING: Stops containers and wipes out the MySQL volume (destroys database data)
clean:
	@echo "Stopping containers and wiping data volumes..."
	docker compose --env-file $(ENV_FILE) down -v

# Debug command to see exactly what values Compose is loading
debug:
	docker compose --env-file $(ENV_FILE) config
