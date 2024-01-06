```
docker build -f .docker/local/Dockerfile -t base/api:latest .docker/local --no-cache
```
```
docker compose -f .docker/local/compose/docker-compose.yml -p base --project-directory . up -d
```