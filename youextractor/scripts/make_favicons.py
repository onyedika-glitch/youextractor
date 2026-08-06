#!/usr/bin/env python3
"""
Generate transparent logo + favicons for YouExtractor.

What it does
------------
1. Removes the background from the source logo.
   - If `rembg` is installed, uses AI background removal (best quality).
   - Otherwise falls back to a centered crop of the neon badge with
     rounded-corner transparency (no extra dependencies beyond Pillow).
2. Emits a full favicon set into public/ so the logo shows in the browser tab:
     favicon.ico            (multi-size 16/32/48 — auto-fetched by browsers)
     favicon-16x16.png
     favicon-32x32.png
     favicon-48x48.png
     apple-touch-icon.png   (180x180, for iOS home screen)
     android-chrome-192x192.png
     android-chrome-512x512.png
     img/youextractor-logo-transparent.png   (full-res transparent logo)

Usage
-----
    pip install pillow            # required
    pip install rembg onnxruntime # optional, for AI cutout
    python3 scripts/make_favicons.py
"""

import os
import sys

try:
    from PIL import Image, ImageDraw, ImageFilter
except ImportError:
    sys.exit("Pillow is required. Install it with:  pip install pillow")

# Resolve paths relative to the project root (parent of this script's dir).
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PUBLIC = os.path.join(ROOT, "public")
IMG = os.path.join(PUBLIC, "img")

SOURCE = os.path.join(IMG, "youextractor-logo.png")
TRANSPARENT = os.path.join(IMG, "youextractor-logo-transparent.png")


def remove_background_ai(img):
    """Try AI background removal via rembg. Returns RGBA image or None."""
    try:
        from rembg import remove
    except ImportError:
        return None
    print("Using rembg (AI) for background removal...")
    return remove(img.convert("RGBA"))


def remove_background_fallback(img):
    """
    No rembg available: isolate the neon badge.

    The source art is a rounded-square neon badge centered on a dark
    circuit-board background. We crop to the badge and mask the corners
    transparent so only the badge remains (its dark glossy interior is
    part of the brand and looks good on both light and dark tab bars).
    """
    print("rembg not found — using centered-crop + rounded-corner fallback.")
    img = img.convert("RGBA")
    w, h = img.size

    # The badge occupies roughly the centre 66% of the frame in this art.
    # Crop a centered square to those bounds.
    inset = 0.16  # fraction trimmed from each edge
    left = int(w * inset)
    top = int(h * inset)
    right = int(w * (1 - inset))
    bottom = int(h * (1 - inset))
    badge = img.crop((left, top, right, bottom))

    bw, bh = badge.size
    side = min(bw, bh)
    badge = badge.crop((0, 0, side, side))

    # Build a rounded-rectangle alpha mask (supersampled for smooth edges).
    scale = 4
    big = side * scale
    radius = int(big * 0.22)  # matches the badge's own corner radius
    mask = Image.new("L", (big, big), 0)
    draw = ImageDraw.Draw(mask)
    draw.rounded_rectangle([0, 0, big - 1, big - 1], radius=radius, fill=255)
    mask = mask.resize((side, side), Image.LANCZOS)
    mask = mask.filter(ImageFilter.GaussianBlur(0.6))

    out = Image.new("RGBA", (side, side), (0, 0, 0, 0))
    out.paste(badge, (0, 0), mask)
    return out


def autocrop_alpha(img, pad_ratio=0.04):
    """Trim fully-transparent margins, then add a little uniform padding."""
    bbox = img.getbbox()
    if bbox:
        img = img.crop(bbox)
    w, h = img.size
    side = max(w, h)
    pad = int(side * pad_ratio)
    canvas = Image.new("RGBA", (side + 2 * pad, side + 2 * pad), (0, 0, 0, 0))
    canvas.paste(img, ((canvas.width - w) // 2, (canvas.height - h) // 2), img)
    return canvas


def main():
    if not os.path.exists(SOURCE):
        sys.exit(f"Source logo not found: {SOURCE}")

    src = Image.open(SOURCE)
    print(f"Loaded {SOURCE}  ({src.size[0]}x{src.size[1]}, {src.mode})")

    cut = remove_background_ai(src)
    if cut is None:
        cut = remove_background_fallback(src)

    cut = autocrop_alpha(cut)

    # Save the full-resolution transparent logo.
    cut.save(TRANSPARENT)
    print(f"Wrote {TRANSPARENT}")

    # PNG favicons at common sizes.
    png_sizes = {
        "favicon-16x16.png": 16,
        "favicon-32x32.png": 32,
        "favicon-48x48.png": 48,
        "apple-touch-icon.png": 180,
        "android-chrome-192x192.png": 192,
        "android-chrome-512x512.png": 512,
    }
    for name, size in png_sizes.items():
        icon = cut.resize((size, size), Image.LANCZOS)
        path = os.path.join(PUBLIC, name)
        icon.save(path)
        print(f"Wrote {path}  ({size}x{size})")

    # Multi-resolution .ico (browsers auto-request /favicon.ico).
    ico_path = os.path.join(PUBLIC, "favicon.ico")
    cut.save(ico_path, sizes=[(16, 16), (32, 32), (48, 48)])
    print(f"Wrote {ico_path}  (16/32/48)")

    print("\nDone. Favicons generated in public/.")


if __name__ == "__main__":
    main()
