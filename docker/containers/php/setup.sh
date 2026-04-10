#!/bin/sh

set -e

echo "📦 Instalando dependências"
composer install --no-interaction --optimize-autoloader || {
    echo "❌ Falha na instalação das dependências"
    exit 1
}

echo "🚀 Iniciando o container"
exec php-fpm
