websockets:
		php artisan websockets:serve

serve:
		pwd

composer-install:
			docker run -it -v $(pwd):/app -w /app --user $(id -u):$(id -g) --rm my-php composer install



