rebuild:
	@docker-compose up -d --build && docker-compose start

start:
	@docker-compose up -d && docker-compose start

stop:
	@docker-compose stop

restart: stop start

connect_app:
	@docker exec -it app bash

connect_nginx:
	@docker exec -it nginx bash

connect_redis:
	@docker exec -it redis bash

connect_mysql:
	@docker exec -it mysql bash

run_queue:
	@docker exec app php artisan queue:work

run_schedule:
	@docker exec app php artisan schedule:work
