FROM mariadb:latest


# SETUP TIME ZONE (UTC)
RUN rm -f /etc/localtime \
  && ln -s /usr/share/zoneinfo/Etc/UTC /etc/localtime

# CONFIGURE MARIADB
RUN chmod -R 0644 /etc/mysql/conf.d