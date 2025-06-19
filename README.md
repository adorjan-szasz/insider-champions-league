# Insider Champions League — Football League Simulator

### Welcome to **Insider Champions League**, a dynamic web application built with Laravel and Vue.js that simulates football leagues, including standings, weekly matches, and title-winning probabilities.

---

## Features

- **Simulate Matches** week by week, all at once or all non-played matches
- **Live Standings Table** auto-sorted by points, goal difference, goals scored
- **Champion Prediction Engine** using weighted probability
- League management: teams, matches, weeks
- Powered by **Laravel API** & reactive **Vue.js frontend**
- Built with modular Vuex store, Axios, and Eloquent ORM

---

## Tech Stack

| Frontend | Backend       | Dev Tools       |
|----------|---------------|-----------------|
| Vue 3    | Laravel 12.18 | Vite            |
| Vuex     | PHP 8.3       | Laravel Artisan |
| Axios    | MySQL         | Composer, NPM   |
|          | Eloquent      | Docker          |

---

## Installation

```bash
git clone https://github.com/adorjan-szasz/insider-champions-league.git
or via SSH
git clone git@github.com:adorjan-szasz/insider-champions-league.git

cd insider-champions-league
```

## Build docker

```bash
docker-compose up --build -d
```

### Install PHP dependencies
```bash
docker exec -it champions_app_container composer install
```

### Setup environment
```bash
cp .env.example .env
docker exec -it champions_app_container php artisan key:generate
```

### Configure DB
```bash
docker exec -it champions_app_container php artisan migrate --seed
```

### Install JS dependencies (also, this is done automatically when building Docker)
```bash
docker exec -it champions_app_container npm install
```

### API Overview

All data (leagues, standings, matches) is provided via a RESTful API.

### Tests

#### Unit Tests:
```bash
docker exec -it champions_app_container php artisan test --testsuite=Unit
```

#### Feature Tests:
```bash
docker exec -it champions_node php artisan test --testsuite=Feature
```

### API Docs

#### Generate:
```bash
docker exec -it champions_app_container composer swagger
```

#### Swagger UI:
http://localhost:8080/api/documentation