# Rossignolb2b - Docker Images

## Images built for Agora

* php-dev
* nginx-dev

Both images contain all application files in /var/www/symfony.

Image _nginx-dev_ should be built **after** _php-dev_ since it relies on it
for all files in `public/`.

### Tags

Images are tagged with following format: ${branchname}-${hash},
with _hash_ being the last commit abbreviated hash.

