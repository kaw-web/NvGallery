#!/bin/bash
set -e

# Installer les dépendances Composer
composer install

# Installer les dépendances Node.js
npm install

# Compiler les assets
npm run dev

php bin/console messenger:consume async