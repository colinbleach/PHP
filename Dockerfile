FROM ubuntu

RUN sudo apt-get update -y
RUN sudo apt-get upgrade -y
RUN apt-get install -y nano wget dialog net-tools

RUN sudo apt-get install -y nginx
RUN sudo apt-get install -y php5
RUN sudo apt-get install -y curl php5-curl wget php5-common php5-cgi php5-cli php5-fpm php5-pgsql htop

RUN sudo curl -LsS http://symfony.com/installer -o /usr/local/bin/symfony
RUN sudo chmod a+x /usr/local/bin/symfony

RUN rm -v /etc/nginx/nginx.conf
RUN sudo curl -sS https://getcomposer.org/installer | php
RUN sudo cp composer.phar /opt/
ADD composer.json /opt/
RUN cd /opt/ && sudo php /opt/composer.phar install; exit 0
RUN cd

# Copy a configuration file from the current directory
ADD nginx.conf /etc/nginx/
ADD index.php /opt/
ADD test.php /opt/
ADD config.php /opt/
ADD startscript.sh /opt/
RUN sudo chmod 777 /opt/startscript.sh

RUN echo "daemon off;" >> /etc/nginx/nginx.conf

RUN apt-get install -y supervisor

RUN mkdir -p /var/log/supervisor

ADD supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN sudo chmod go+rw /var/run

EXPOSE 80 5432

CMD supervisord -c /etc/supervisor/conf.d/supervisord.conf