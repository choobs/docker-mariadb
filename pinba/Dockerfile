FROM alpine:3.3
MAINTAINER Erik DeLamarter <erik.delamarter@choobs.com>

RUN echo "@testing http://dl-4.alpinelinux.org/alpine/edge/testing" >> /etc/apk/repositories
RUN apk add --update \
		bash \
		judy@testing \
		libevent \
		mariadb \
		mariadb-client \
		protobuf \
		pwgen \
		tzdata \
		&& rm -rf /var/cache/apk/*

COPY build-pinba.sh /
RUN	/build-pinba.sh

RUN	mkdir -p /var/run/mysqld && \
		chown mysql:mysql /var/run/mysqld && \
		mkdir -p /etc/mysql/conf.d

COPY my.cnf /etc/mysql/

VOLUME /var/lib/mysql

COPY docker-entrypoint.sh /
RUN mkdir /docker-entrypoint-initdb.d

ENTRYPOINT ["/docker-entrypoint.sh"]

EXPOSE 3306 30002
CMD ["mysqld"]
