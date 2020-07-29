###Demo backend
1) Run `docker-compose up --build`, composer will install needed dependencies

2) On first run you'll need to run migrations and seeds:
`docker exec -it test-task-php php artisan migrate --seed`

2) Server will be available by url: `http://localhost:6060/`
