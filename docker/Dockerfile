FROM Docker.dafiti.io/tricae/web:php7-rabbit
MAINTAINER Luiz Fumes <lcfumes@gmail.com>

RUN apt-get update
RUN apt-get install -y php-pear 

WORKDIR /project

EXPOSE 80

CMD ["/usr/bin/supervisord"]
