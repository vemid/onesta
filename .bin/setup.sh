#!/bin/sh

HOME_DIR="${PWD}";
HOOK_DIR="${PWD}/.git-hooks";

if [ ! -L .git/hooks ];
then
  git init
  echo ".git/hooks is not symlink"
  echo "copying .git/hooks to .git/old_hooks"
  mv .git/hooks .git/old_hooks

  echo "symlinking $HOOK_DIR/.git-hooks .git/hooks"
  ln -s ${HOOK_DIR} .git/hooks
  git config core.hooksPath .git/hooks
else
  echo ".git/hooks is already a symlink"
fi

if [ ! -d "${HOME_DIR}/public/assets" ];
then
  echo "creating assets folder in $HOME_DIR/public"
  mkdir public/assets
  chmod 777 public/assets
else
  echo "assets folder exists"
fi

if [ ! -d "${HOME_DIR}"/var ];
then
  echo "creating var folder in $HOME_DIR"
  mkdir public/assets
  chmod 777 "${HOME_DIR}/var"
else
  echo "var folder exists"
fi

if [ ! -d "${HOME_DIR}/var/cache/doctrine" ];
then
  echo "creating doctrine folder in $HOME_DIR/var/cache"
  mkdir -p var/cache/doctrine
  chmod 777 var/cache/doctrine
else
  echo "doctrine folder exists"
fi

if [ ! -d "${HOME_DIR}/var/cache/assets-cache" ];
then
  echo "creating doctrine folder in $HOME_DIR/var/assets-cache"
  mkdir -p var/cache/assets-cache
  chmod 777 var/cache/assets-cache
else
  echo "assets-cache folder exists"
fi

if [ ! -d "${HOME_DIR}/var/cache/language" ];
then
  echo "creating language folder in $HOME_DIR/var/cache"
  mkdir -p var/cache/language
  chmod 777 var/cache/language
else
  echo "language folder exists"
fi

if [ ! -d "${HOME_DIR}"/var/uploads ];
then
  echo "creating uploads folder in $HOME_DIR/var"
  mkdir var/uploads
  chmod 777 "${HOME_DIR}/var/uploads"
else
  echo "uploads folder exists"
fi

if [ ! -L public/uploads ];
then
  echo "uploads folder is not symlinked"

  echo "symlinking $HOME_DIR/var/uploads $HOME_DIR/public"
  ln -s "../var/uploads" "./public/uploads"
else
  echo "uploads is already a symlinked"
fi

if [ ! -L public/fonts ];
then
  echo "fonts folder is not symlinked"

  echo "symlinking $HOME_DIR/build/fonts $HOME_DIR/public"
  ln -s "../build/fonts" "./public/fonts"
else
  echo "fonts is already a symlinked"
fi

if [ ! -L public/img ];
then
  echo "img folder is not symlinked"

  echo "symlinking $HOME_DIR/build/img $HOME_DIR/public"
  ln -s "../build/img" "./public/img"
else
  echo "img folder is already a symlinked"
fi

echo "setup finished"

