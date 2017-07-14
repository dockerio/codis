FROM ubuntu:latest
MAINTAINER mytwemproxy "lidandan2017@gmail.com"
COPY ./pip.conf ~/.pip/pip.conf
RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list
RUN apt-get update -y && \
    apt-get upgrade -y && \
    apt-get install make git curl autoconf automake vim nano redis-tools inetutils-ping python-pip libtool -y
WORKDIR /opt/redis
RUN git clone https://github.com/twitter/twemproxy.git && \
    cd twemproxy && autoreconf -fvi && \
    ./configure --enable-debug=full && \
    make
RUN pip install -U pip && \
    pip install nose -U && \
    pip install git+https://github.com/andymccurdy/redis-py.git@2.10.3 -U && \
    pip install git+https://github.com/idning/python-memcached.git#egg=memcache -U

WORKDIR /opt/run
EXPOSE 2121
EXPOSE 22222
CMD ["/opt/redis/twemproxy/src/nutcracker", "-c", "conf/nutcracker.yml"]
