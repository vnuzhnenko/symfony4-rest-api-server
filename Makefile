build:
	docker run --rm -i -t -v $(PWD):/app composer install
	docker build -t todoapp .

run: build run-only

run-only:
	docker run -it --rm -p 8080:8080 --name todoapp todoapp
