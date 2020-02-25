#!/usr/bin/env bash

set -euo pipefail

/usr/local/bin/php /usr/local/bin/composer install

/usr/local/bin/php /app/bin/predictions.php predictions:generate
