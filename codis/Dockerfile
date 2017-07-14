FROM phusion/baseimage:latest

MAINTAINER Li Dandan "lidandan2017@gmail.com"

COPY ./pip.conf ~/.pip/pip.conf
RUN sed -i 's/archive.ubuntu.com/mirrors.ustc.edu.cn/g' /etc/apt/sources.list && \
    apt-get -y update && \
    apt-get -y upgrade

RUN apt-get install -y git curl make autoconf automake golang net-tools

ENV GOPATH /opt/go
RUN mkdir -p $GOPATH/src/github.com/CodisLabs && \
    cd $GOPATH/src/github.com/CodisLabs && git clone https://github.com/CodisLabs/codis.git

RUN cd $GOPATH/src/github.com/CodisLabs/codis && \
    make -j4

WORKDIR $GOPATH/src/github.com/CodisLabs/codis

COPY ./start.sh /opt/go/src/github.com/CodisLabs/codis/start.sh

RUN mkdir -p /etc/my_init.d
COPY ./run /etc/my_init.d/codis.sh
RUN chmod +x /etc/my_init.d/codis.sh && \
    chmod +x /opt/go/src/github.com/CodisLabs/codis/start.sh

CMD ["/sbin/my_init"]
