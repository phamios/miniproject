<h1 align="center">Server</h1>

## Server
## Requirements
Before setup the project, you need to install this : 
- **Docker Compose**

## Installation Guide
- Run `docker-compose up -d --build`
- Run `docker-compose exec app composer install`
- Run `docker-compose exec app npm install`
- Copy `env.example` to `.env`
- Copy `env.example` to `.env.testing` for unit testing
- Run `docker-compose exec app php artisan migrate:refresh --seed`
- Run `docker-compose exec app php artisan key:generate`
- For unit testing Run `docker-compose exec app php artisan test`

## URL 
- Web : `http://localhost:8080`
- DB / MongoDB : `http://localhost:27018`
- API Document : `http://localhost:8080/api/v1/documentation`

<h1 align="center">Client</h1>
## Installation Guide
- Run npm install to install all dependencies
- Create ENV file :
	- VITE_YOUTUBE_API_KEY=xxxxx 
	- API_URL=http://localhost:8080/api/v1/
- run `npm run dev ` : to run testing

- run `npm run build`: to build for production
