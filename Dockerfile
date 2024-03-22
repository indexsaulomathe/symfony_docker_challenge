# Utilize a imagem oficial do PHP com a versão FPM para PHP 8.2
FROM php:8.2-fpm

# Instalar dependências do sistema para extensões PHP e utilitários necessários
# Nota: A instrução 'apk' é usada em imagens baseadas no Alpine. Para imagens Debian/Ubuntu, use 'apt-get'.
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_pgsql

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
# Isso é necessário para que o servidor web possa escrever nos diretórios 'var' e 'vendor', por exemplo
RUN chown -R www-data:www-data /var/www/symfony

# Expõe a porta 9000 para o servidor PHP-FPM
EXPOSE 9000

# Comando para iniciar o servidor PHP-FPM
CMD ["php-fpm"]
