FROM alpine:latest
MAINTAINER Li Dandan <lidandan2017@gmail.com>

RUN echo "ipv4" >> /etc/modules && echo "ipv6" >> /etc/modules && \
    sed -i 's/http:\/\/dl-cdn.alpinelinux.org/https:\/\/mirrors.ustc.edu.cn/g' /etc/apk/repositories && \
    sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories

RUN apk update && \
    apk --no-cache --progress -v -f -U add tini build-base git curl wget python-dev ca-certificates

ENTRYPOINT ["/sbin/tini", "--"]
