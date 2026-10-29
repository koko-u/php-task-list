workspace_root := justfile_directory()

_default:
    @just --list

# run server
run:
   @php artisan serve --port=3000 --no-reload
# run npm
asset:
   @pnpm run dev

# run server and npm
[parallel]
serve: run asset

# lint
lint:
    @vendor/bin/phpstan analyse --memory-limit=512M

# format
fmt:
    @vendor/bin/pint


# docker up
dup:
    @docker compose -f {{ workspace_root }}/docker/compose.yml up -d

# docker down
dwn:
    @docker compose -f {{ workspace_root }}/docker/compose.yml down

# print migration sql
# マイグレーションの SQL をクリップボードにコピー
msql:
    @php artisan migrate --pretend --no-ansi \
        | sed -n 's/^[[:space:]]*⇂[[:space:]]*//p' \
        | sed 's/[[:space:]]*$//; s/$/;/'

# dbmate rollback all
rollback-all:
    #!/usr/bin/env bash
    set -euo pipefail

    count=$(dbmate status | awk '/^Applied:/ { print $2 }')

    echo "Rolling back $count migration(s)..."

    for ((i = 0; i < count; i++)); do
        dbmate rollback
    done
