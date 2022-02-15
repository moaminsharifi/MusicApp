# DevDoc - Documentation for Developers
## Setup docker
#### Setup Network

```bash
docker network create music-app_nginx-reverse-proxy-net
```

#### Build Docker images

```bash
docker-compose build
```
#### Run Docker images with `docker-compose`
```bash
docker-compose up
```
- use `-d` flag if want to detached after containers up
- use `--force-recreate` flag for re create docker containers 
- use `--build` flag for re create docker images

#### Connect to docker container
```
docker exec -ti NAME bash 
```
use NAME to specify container name, normally docker container name is:

- `musicapp_music-app-backend_1` for backend
 - `musicapp_music-app-db_1` for database


## Database Design in DB diagram
![Database Design Of Music App](/assets/DatabaseDesign.png)

- [Download Pdf version of database](/assets/DatabaseDesign.pdf)
- [See Online version in dbdiagram](https://dbdiagram.io/d/620b9b8f85022f4ee598fca9)

## API Example and design
[Link of documentation](/backend/docs/index.html)