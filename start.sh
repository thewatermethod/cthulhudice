#!/bin/sh

## first, we close open containers
wp-env stop

## spin up docker
wp-env start

## start up browser-sync
npm run dev