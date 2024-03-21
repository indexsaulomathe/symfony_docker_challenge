# Utilize a imagem PHP com a versão FPM
FROM php:8.1-fpm-alpine

# Instalar dependências do sistema para extensões PHP e utilitários necessários
RUN apk update && apk add --no-cache \
    postgresql-dev \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_pgsql

# Definir o diretório de trabalho no container
WORKDIR /var/www/symfony

# Instalar o Composer no container
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copiar apenas o composer.json e o composer.lock para instalar dependências
COPY composer.json composer.lock ./

# Instalar dependências do projeto via Composer
# A flag --no-scripts é utilizada para evitar a execução de scripts que podem precisar do app já configurado
# A flag --no-dev é para evitar instalar pacotes de desenvolvimento em produção
RUN composer install --no-scripts --no-dev --prefer-dist --optimize-autoloader

# Copiar o restante do código fonte do projeto para o container
COPY . .

# Configurar permissões do diretório
RUN chown -R www-data:www-data /var/www/symfony

# Expõe a porta 9000 e inicia o servidor PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]