# MySQL disk usage

The Sail MySQL container (`mysql/mysql-server:8.0`) enables binary logging by default.
Without replication this only consumes disk space in the `sail-mysql` Docker volume until the host runs out of space, producing errors such as:

```
Disk is full writing './binlog.000036' (OS errno 28 - No space left on device)
```

## What changed
- `docker-compose.yml` now starts MySQL with `--disable-log-bin`, which prevents new binary log files from being created for local development.
- Remove that command only if you actually need replication or PITR locally.

## Cleaning up existing binary logs
1. Stop Sail: `./vendor/bin/sail down`.
2. Remove the existing log files from the volume. Any of the following approaches works:
   - Inspect the volume path and delete the `binlog.*` files manually:
     ```bash
     docker volume inspect sail-mysql # note the Mountpoint path
     sudo rm -f /var/lib/docker/volumes/sail-mysql/_data/binlog.*
     ```
   - Or start a throwaway container that mounts the volume and cleans it:
     ```bash
     docker run --rm -v sail-mysql:/var/lib/mysql alpine:3.19 \
       sh -c 'rm -f /var/lib/mysql/binlog.* /var/lib/mysql/binlog.index'
     ```
3. Start Sail again: `./vendor/bin/sail up -d`.

If MySQL is still running and you have enough free space to connect, you can also purge the logs from inside MySQL instead of deleting files:

```bash
./vendor/bin/sail mysql -uroot -p"$DB_PASSWORD" -e "PURGE BINARY LOGS BEFORE NOW() - INTERVAL 1 DAY;"
```

After the purge/restart MySQL will no longer produce binary logs, so the disk usage will remain stable.
