#!/bin/sh

echo "NODE_ENV is $NODE_ENV"

set -e

npm install

if [ "$NODE_ENV" = "production" ]; then
  echo "Building Vue app for production..."
  npm run build
else
  echo "Starting Vite dev server..."
  npx vite --host
fi
