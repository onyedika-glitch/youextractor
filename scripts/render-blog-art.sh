#!/usr/bin/env bash
# Render blog illustrations from HTML to images with headless Chrome.
#
#   scripts/render-blog-art.sh <post-slug>
#
# Each resources/blog-art/<slug>/<name>.html must start with a size comment:
#   <!-- size: 1200x630 -->
# Covers (cover.html) are written as PNG for social cards; everything else as WebP.
# Output: public/img/blog/<slug>/<name>.(png|webp)
set -euo pipefail

slug="${1:?usage: $0 <post-slug>}"
root="$(cd "$(dirname "$0")/.." && pwd)"
src="$root/resources/blog-art/$slug"
out="$root/public/img/blog/$slug"
chrome="$(command -v google-chrome || command -v chromium || command -v chromium-browser)"
tmp="$(mktemp -d)"
trap 'rm -rf "$tmp"' EXIT

mkdir -p "$out"

for html in "$src"/*.html; do
  name="$(basename "$html" .html)"
  size="$(grep -oE 'size: *[0-9]+x[0-9]+' "$html" | head -1 | grep -oE '[0-9]+x[0-9]+')"
  w="${size%x*}"; h="${size#*x}"

  # Render at 2x for sharp text, then scale down to the target size.
  "$chrome" --headless=new --disable-gpu --hide-scrollbars --force-device-scale-factor=2 \
    --virtual-time-budget=4000 --window-size="$w,$h" \
    --screenshot="$tmp/$name.png" "file://$html" >/dev/null 2>&1

  if [ "$name" = "cover" ]; then
    convert "$tmp/$name.png" -resize "${w}x${h}" -strip "$out/$name.png"
  else
    convert "$tmp/$name.png" -resize "$((w * 3 / 2))x" -strip -quality 86 "$out/$name.webp"
  fi
  echo "rendered $name (${w}x${h})"
done
