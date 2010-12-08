#!/bin/bash

base="$1"
force="$2"
if [ -z "$base" ]; then
	echo "usage $0: <basedir>"
	exit
fi

if [ "$force" != "--force" ]; then
	echo "this will erase all your game data you must pass --force"
	exit
fi

rm -rf $base/games/icons/*
rm -rf $base/games/large/*
rm -rf $base/games/media/*
rm -rf $base/games/thumbs/*

echo "complete"
