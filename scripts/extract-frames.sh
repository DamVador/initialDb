#!/usr/bin/env bash
# ============================================================
# extract-frames.sh — régénère la séquence WebP de la section Méthode
# depuis la vidéo source, pour l'effet de défilement au scroll.
#
# Prérequis : ffmpeg + cwebp (brew install ffmpeg webp)
# Usage :     bash scripts/extract-frames.sh
# ============================================================
set -euo pipefail
cd "$(dirname "$0")/.."

SRC="assets/video/car-drive-out.mp4"
OUT="assets/video/frames-out"
QUALITY=70   # qualité WebP (0-100)

# Recadrage pour retirer les bandes noires de la source (cf. cropdetect).
CROP="crop=1280:666:0:26"

echo "→ Extraction PNG temporaires (recadrées)…"
TMP="$(mktemp -d)"
ffmpeg -y -loglevel error -i "$SRC" -vf "$CROP" "$TMP/frame-%04d.png"

echo "→ Conversion en WebP (q$QUALITY)…"
mkdir -p "$OUT"
for f in "$TMP"/frame-*.png; do
  b="$(basename "$f" .png)"
  cwebp -quiet -q "$QUALITY" -m 6 "$f" -o "$OUT/$b.webp"
done

rm -rf "$TMP"
N="$(ls "$OUT"/*.webp | wc -l | tr -d ' ')"
echo "✅ $N frames WebP générées dans $OUT"
echo "   (pense à ajuster data-count=\"$N\" dans la section Méthode si le nombre change)"
