#!/usr/bin/env bash

ARGS="$@"

# Baseado na documentação oficial do Drupal para testes PHPUnit no Lando.
# Copia a configuração padrão do PHPUnit do núcleo do Drupal.
cp /app/web/core/phpunit.xml.dist /app/web/core/phpunit.xml

# Configurações específicas para o ambiente Lando.
sed -i 's/<env name="SIMPLETEST_BASE_URL" value=""\/>/<env name="SIMPLETEST_BASE_URL" value="http:\/\/orsed.lndo.site"\/>/' /app/web/core/phpunit.xml
sed -i 's/<env name="SIMPLETEST_DB" value=""\/>/<env name="SIMPLETEST_DB" value="mysql:\/\/drupal9:drupal9@database\/drupal9"\/>/' /app/web/core/phpunit.xml
sed -i 's/<env name="BROWSERTEST_OUTPUT_BASE_URL" value=""\/>/<env name="BROWSERTEST_OUTPUT_BASE_URL" value="http:\/\/orsed.lndo.site"\/>/' /app/web/core/phpunit.xml

# Suporte para diferentes versões do Drupal.
sed -i 's/sites\/simpletest\/browser_output/\/app\/web\/sites\/simpletest\/browser_output/' /app/web/core/phpunit.xml
sed -i 's/<env name="BROWSERTEST_OUTPUT_DIRECTORY" value=""\/>/<env name="BROWSERTEST_OUTPUT_DIRECTORY" value="\/app\/web\/sites\/simpletest\/browser_output\/"\/>/' /app/web/core/phpunit.xml

# Cria diretórios necessários e ajusta permissões.
mkdir -p /app/web/sites/simpletest/browser_output
chmod -R 777 /app/web/sites/simpletest/browser_output

# Remove resultados anteriores.
rm -f /app/web/sites/simpletest/browser_output/*.html
rm -f /app/web/sites/simpletest/browser_output/*.counter

# Executa o PHPUnit usando a configuração do núcleo do Drupal.
/app/vendor/bin/phpunit -c /app/web/core $ARGS
