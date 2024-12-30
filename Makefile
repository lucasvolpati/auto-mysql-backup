up: 
	@docker-compose up -d

down: 
	@docker-compose down

unit:
	@./vendor/bin/phpunit $(class) --display-warnings