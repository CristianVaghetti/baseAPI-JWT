#!/bin/bash

/bin/quickstart &

# Inicialização do servidor web
/usr/sbin/apache2ctl -DFOREGROUND
