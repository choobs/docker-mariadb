#!/bin/bash
set -ex

apk add --update alpine-sdk mariadb-dev protobuf-dev judy-dev@testing libtool libevent-dev automake autoconf make

cd /root

git clone --depth 1 git://dev.alpinelinux.org/aports
wget -O - https://github.com/tony2001/pinba_engine/archive/RELEASE_1_1_0.tar.gz | tar -xzf -

cd /root/aports/main/mariadb/
abuild -Fq fetch
abuild -Fq unpack
abuild -Fq prepare

MYSQL_SRC=`find "$PWD/src" -maxdepth 1 -type d -name "mariadb*"`

cp -r /usr/include/mysql/* "$MYSQL_SRC/include/"

cd /root/pinba_engine-RELEASE_1_1_0/

./buildconf.sh 
./configure --with-mysql="$MYSQL_SRC/" --libdir=/usr/lib/mysql/plugin
make install

cat > /usr/lib/mysql/plugin/pinba.ini << EOF
#
# library binary file name (without .so or .dll)
# component_name
# [component_name] - additional components in plugin
#
	libpinba_engine.so
	pinba
EOF

rm -r /root/aports
rm -r /root/pinba_engine-RELEASE_1_1_0

apk del alpine-sdk 
apk del autoconf
apk del automake
apk del judy-dev
apk del libtool
apk del libevent-dev
apk del make
apk del mariadb-dev 
apk del protobuf-dev 

rm -rf /var/cache/apk/*
rm -rf /var/cache/distfiles/*