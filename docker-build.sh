#!/bin/bash
docker build -t wordpress_plugin_test:0.1.2 --build-arg USER_ID=$(id -u) --build-arg GROUP_ID=$(id -g) docker/wordpress
