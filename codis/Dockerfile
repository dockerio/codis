FROM ubuntu:latest

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
    make -j4 && \
    apt-get -y install iputils-ping

WORKDIR /opt/go/src/github.com/CodisLabs/codis
