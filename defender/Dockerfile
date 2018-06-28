FROM ubuntu:bionic
ENV S6_OVERLAY_VERSION v1.21.4.0

ADD https://github.com/just-containers/s6-overlay/releases/download/$S6_OVERLAY_VERSION/s6-overlay-amd64.tar.gz /tmp/
RUN tar xzf /tmp/s6-overlay-amd64.tar.gz -C /

ENV DEBIAN_FRONTEND=noninteractive

# RUN curl -s https://packagecloud.io/install/repositories/linyows/octopass/script.deb.sh | bash
RUN apt update
RUN apt -y install tzdata
RUN apt -y install apache2 openssh-server rsyslog curl wget netcat php php-mysqli mysql-server sudo
RUN apt -y install vim iproute2
RUN mkdir /etc/services.d/apache /etc/services.d/sshd /etc/services.d/00rsyslog /etc/services.d/mysql
RUN apt -y install libcurl3-gnutls libjansson4
## FIXME: update install via repo when added bionic repo
RUN wget --content-disposition https://packagecloud.io/linyows/octopass/packages/ubuntu/xenial/octopass_0.4.1-1_amd64.deb/download.deb && \
    dpkg -i ./octopass_0.4.1-1_amd64.deb && rm -f *.deb

ADD octopass.conf /etc/octopass.conf
RUN echo "AuthorizedKeysCommand /usr/bin/octopass" >> /etc/ssh/sshd_config
RUN echo "AuthorizedKeysCommandUser root" >> /etc/ssh/sshd_config
ADD pam.d-sshd /etc/pam.d/sshd
ADD nsswitch.conf /etc/nsswitch.conf

ADD rsyslog-run /etc/services.d/00rsyslog/run
ADD sshd-run /etc/services.d/sshd/run
ADD apache-run /etc/services.d/apache/run
ADD mysql-run /etc/services.d/mysql/run
ADD initalizers.sh /etc/cont-init.d/initalizers.sh

RUN chmod a+x /etc/services.d/*/* /etc/cont-init.d/*

ADD ./phpinfo.php /var/www/html/phpinfo.php
ADD ./app /var/www/html/app
RUN chown 2000:2000 -R /var/www/html
RUN chmod g+w -R /var/www/html
RUN echo '%bigbridge ALL=(ALL) NOPASSWD: ALL' > /etc/sudoers.d/octopass
RUN chmod 440 /etc/sudoers.d/octopass

EXPOSE 80
EXPOSE 22

ENTRYPOINT ["/init"]
