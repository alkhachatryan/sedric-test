rebuild:
	@docker-compose up -d --build && docker-compose start

start:
	@docker-compose up -d && docker-compose start

stop:
	@docker-compose stop

restart: stop start

connect_app:
	@docker exec -it sedric_app_1 bash

connect_nginx:
	@docker exec -it sedric_nginx_1 bash

connect_redis:
	@docker exec -it sedric_redis_1 bash

connect_mysql:
	@docker exec -it sedric_mysql_1 bash
