rebuild:
	@docker-compose up -d --build && docker-compose start

start:
	@docker-compose up -d && docker-compose start

stop:
	@docker-compose stop

restart: stop start

connect_app:
	@docker exec -it sedric_test_app bash

connect_nginx:
	@docker exec -it sedric_test_nginx bash

connect_redis:
	@docker exec -it sedric_test_redis bash

connect_mysql:
	@docker exec -it sedric_test_mysql bash

run_queue:
	@docker exec sedric_test_app php artisan queue:work

run_schedule:
	@docker exec sedric_test_app php artisan schedule:work
