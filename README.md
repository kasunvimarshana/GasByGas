"# GasByGas" 

### Key Instructions:
- **Generate Self-Signed SSL Certificate**: `openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout docker/certs/localhost.key -out docker/certs/localhost.crt`
- **Start Containers**: `docker-compose up -d`
- **Access the PHP Container**: `docker exec -it gasbygas_app bash`