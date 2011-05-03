#!/bin/sh
cat /dev/zero > zero.fill;sync;sleep 1;sync;rm -f zero.fill
