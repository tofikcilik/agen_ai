#!/usr/bin/env bash
set -euo pipefail

BASE_DIR="/opt/workspace/airbersih"
REPO_DIR="${BASE_DIR}/repo/agen_ai"

echo "Masuk ke source repo..."
cd "${REPO_DIR}"

echo "Ambil perubahan terbaru dari GitHub..."
git pull --ff-only

echo "Build dan restart stack..."
cd "${BASE_DIR}"
docker compose build --no-cache
docker compose up -d

echo "Bersihkan image lama yang tidak terpakai..."
docker image prune -f

echo "Deploy selesai."
