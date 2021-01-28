.PHONY: build composer icons clean generate

VERSION=0.4.2

build: composer icons
	php gen.php heroicons-$(VERSION)

clean:
	rm -rf heroicons* composer.lock vendor

composer: composer.lock

composer.lock: composer.json
	composer install

icons: heroicons-$(VERSION)/package.json

heroicons-$(VERSION)/package.json:
	rm -rf heroicons*
	curl -L https://github.com/tailwindlabs/heroicons/archive/v$(VERSION).zip > icons.zip
	unzip icons
	rm icons.zip
