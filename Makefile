.PHONY: css demo-server

css:
	npx tailwindcss-cli build ./src/admin.css -o ./demo/css/admin.css
demo-server:
	php -S 127.0.0.1:8000 -t demo demo/index.php
