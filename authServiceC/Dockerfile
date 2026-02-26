FROM debian:stable-slim

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    curl \
    ca-certificates \
    gnupg \
    bash \
    git \
    unzip \
    zip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    supervisor \
    lsb-release \
    apt-transport-https \
    nginx \
    openssh-client \
    vim \
    protobuf-compiler \
    gcc \
    g++ \
    make \
    build-essential \
    libssl-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js (latest LTS) and Yarn, then install protoc-gen-ts_proto globally using yarn and ensure yarn global bin is in PATH
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get update && apt-get install -y nodejs \
    && npm install -g yarn \
    && node -v \
    && yarn -v \
    && yarn global add ts-proto \
    && export PATH="$PATH:$(yarn global bin)"

# Copy project files and configuration
COPY . .
COPY ./.env.example .env
COPY files/supervisor/* /opt/docker/etc/supervisor.d/
COPY files/supervisor/* /etc/supervisor/conf.d/
RUN chmod +x /etc/supervisor/conf.d/*

# Replace default nginx config
RUN rm /etc/nginx/sites-enabled/default
COPY files/nginx/nginx.conf /etc/nginx/conf.d/default.conf

# Set supervisor log directory
RUN mkdir -p /var/log/supervisor/ && chmod 777 /var/log/supervisor/

# Entrypoint
COPY files/scripts/* /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]