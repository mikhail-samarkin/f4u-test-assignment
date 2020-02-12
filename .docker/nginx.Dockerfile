FROM nginx:latest

ARG TIMEZONE
ARG DOCKER_PROJECT_DIR

# fix timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    && echo ${TIMEZONE} > /etc/timezone \
    && date

WORKDIR ${DOCKER_PROJECT_DIR}