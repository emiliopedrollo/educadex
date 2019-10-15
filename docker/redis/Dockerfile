FROM redis:alpine
RUN mkdir /var/lib/redis
VOLUME /var/lib/redis
CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]
EXPOSE 6379
