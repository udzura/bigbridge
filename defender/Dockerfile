FROM ubuntu:bionic
ENV S6_OVERLAY_VERSION v1.21.4.0

ADD https://github.com/just-containers/s6-overlay/releases/download/$S6_OVERLAY_VERSION/s6-overlay-amd64.tar.gz /tmp/
RUN tar xzf /tmp/s6-overlay-amd64.tar.gz -C /

RUN apt update
RUN apt -y install apache2 openssh-server rsyslog
RUN mkdir /etc/services.d/apache /etc/services.d/sshd /etc/services.d/00rsyslog

ADD rsyslog-run /etc/services.d/00rsyslog/run
ADD sshd-run /etc/services.d/sshd/run
ADD apache-run /etc/services.d/apache/run
ADD initalizers.sh /etc/cont-init.d/initalizers.sh

RUN chmod a+x /etc/services.d/*/* /etc/cont-init.d/*

ENTRYPOINT ["/init"]
