import os
from PIL import Image, ImageDraw, ImageFont

def create_diagonal_gradient(width, height, color1, color2):
    # Create a small 2x2 image to interpolate colors
    base = Image.new("RGB", (2, 2))
    base.putpixel((0, 0), color1)  # Top-left
    
    # Midpoint color
    mid_color = tuple(int((c1 + c2) / 2) for c1, c2 in zip(color1, color2))
    base.putpixel((1, 0), mid_color)
    base.putpixel((0, 1), mid_color)
    base.putpixel((1, 1), color2)  # Bottom-right
    
    return base.resize((width, height), Image.Resampling.BILINEAR)

def add_rounded_corners(img, radius):
    mask = Image.new("L", img.size, 0)
    draw = ImageDraw.Draw(mask)
    draw.rounded_rectangle((0, 0) + img.size, radius, fill=255)
    result = img.copy()
    result.putalpha(mask)
    return result

def draw_badge(draw, x, y, text, font, bg_color, border_color, text_color):
    text_bbox = draw.textbbox((0, 0), text, font=font)
    text_width = text_bbox[2] - text_bbox[0]
    text_height = text_bbox[3] - text_bbox[1]
    
    padding_x = 14
    padding_y = 8
    badge_width = text_width + 2 * padding_x
    badge_height = text_height + 2 * padding_y
    
    draw.rounded_rectangle(
        (x, y, x + badge_width, y + badge_height), 
        8, 
        fill=bg_color, 
        outline=border_color, 
        width=1
    )
    # Draw text inside the badge
    draw.text((x + padding_x, y + padding_y - 2), text, font=font, fill=text_color)
    return badge_width

def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    logo_path = os.path.join(base_dir, 'chrome-extension', 'logo.jpg')
    screenshot_path = os.path.join(base_dir, 'public', 'img', 'app-screenshot-1.png')
    
    # Paths to fonts
    font_bold_path = "/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf"
    font_regular_path = "/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf"
    
    if not os.path.exists(font_bold_path):
        font_bold_path = None  # Fallback to default
        font_regular_path = None
        print("⚠️ System font not found, falling back to default PIL font.")
        
    # Color Palette (Light Theme / White background)
    bg_start = (255, 255, 255)       # White
    bg_end = (248, 250, 252)         # Slate 50 (Very light gray)
    primary_text = (15, 23, 42)       # Slate 900 (Dark Slate)
    secondary_text = (71, 85, 105)    # Slate 600 (Medium Slate Gray)
    accent_purple = (109, 40, 217)    # Violet 700 (Deep Purple)
    border_color = (226, 232, 240)    # Slate 200 (Light border)
    
    # ----------------------------------------------------
    # 1. CREATE SMALL PROMO TILE (440x280)
    # ----------------------------------------------------
    print("Generating Small Promo Tile (440x280)...")
    small_tile = create_diagonal_gradient(440, 280, bg_start, bg_end)
    draw_small = ImageDraw.Draw(small_tile)
    
    # Draw a thin light border around the outer edge of the tile
    draw_small.rectangle((0, 0, 439, 279), outline=border_color, width=1)
    
    # Load and process logo
    if os.path.exists(logo_path):
        with Image.open(logo_path) as logo_img:
            logo_resized = logo_img.resize((100, 100), Image.Resampling.LANCZOS)
            logo_rounded = add_rounded_corners(logo_resized, 16)
            small_tile.paste(logo_rounded, (35, 90), logo_rounded)
    else:
        print("⚠️ Logo file not found. Skipping logo paste for small tile.")
    
    # Load Fonts
    if font_bold_path:
        font_title = ImageFont.truetype(font_bold_path, 34)
        font_tagline = ImageFont.truetype(font_regular_path, 16)
        font_subtagline = ImageFont.truetype(font_bold_path, 13)
    else:
        font_title = ImageFont.load_default()
        font_tagline = ImageFont.load_default()
        font_subtagline = ImageFont.load_default()
        
    # Draw Text
    draw_small.text((155, 95), "YouExtractor", font=font_title, fill=primary_text)
    draw_small.text((155, 145), "YouTube to Code & Course", font=font_tagline, fill=secondary_text)
    draw_small.text((155, 172), "AI-Powered Project Generator", font=font_subtagline, fill=accent_purple)
    
    # Save Small Tile
    small_output_path = os.path.join(base_dir, 'chrome-extension', 'promo_small.png')
    small_tile.save(small_output_path, "PNG")
    print(f"✅ Small Promo Tile saved to {small_output_path}")
    
    # ----------------------------------------------------
    # 2. CREATE MARQUEE PROMO TILE (1400x560)
    # ----------------------------------------------------
    print("\nGenerating Marquee Promo Tile (1400x560)...")
    marquee_tile = create_diagonal_gradient(1400, 560, bg_start, bg_end)
    
    # Draw a thin light border around the outer edge of the tile
    draw_marquee_border = ImageDraw.Draw(marquee_tile)
    draw_marquee_border.rectangle((0, 0, 1399, 559), outline=border_color, width=1)
    
    # Draw background glow effect on the right for screenshot (adapted for light background)
    glow_layer = Image.new("RGBA", (1400, 560), (0, 0, 0, 0))
    glow_draw = ImageDraw.Draw(glow_layer)
    for i in range(15):
        offset = i * 4
        # Soft violet shadow/glow behind the screenshot
        glow_draw.rounded_rectangle(
            (640 - offset, 55 - offset, 640 + 720 + offset, 55 + 450 + offset),
            16 + offset,
            fill=(109, 40, 217, int(8 / (i + 1)))
        )
    marquee_tile = Image.alpha_composite(marquee_tile.convert("RGBA"), glow_layer).convert("RGB")
    draw_marquee = ImageDraw.Draw(marquee_tile)
    
    # Load and place screenshot
    if os.path.exists(screenshot_path):
        with Image.open(screenshot_path) as ss_img:
            ss_resized = ss_img.resize((720, 450), Image.Resampling.LANCZOS)
            ss_rounded = add_rounded_corners(ss_resized, 16)
            marquee_tile.paste(ss_rounded, (640, 55), ss_rounded)
            
            # Draw a subtle border around the screenshot
            draw_marquee.rounded_rectangle(
                (640, 55, 640 + 720, 55 + 450),
                16,
                outline=(109, 40, 217, 80), # Soft purple border
                width=2
            )
    else:
        print("⚠️ Screenshot file not found. Skipping screenshot paste for marquee tile.")
        
    # Load Fonts for Marquee
    if font_bold_path:
        font_m_title = ImageFont.truetype(font_bold_path, 76)
        font_m_tagline = ImageFont.truetype(font_regular_path, 26)
        font_m_badge = ImageFont.truetype(font_bold_path, 14)
    else:
        font_m_title = ImageFont.load_default()
        font_m_tagline = ImageFont.load_default()
        font_m_badge = ImageFont.load_default()
        
    # Draw Text
    draw_marquee.text((80, 130), "YouExtractor", font=font_m_title, fill=primary_text)
    
    tagline_text = "Turn YouTube coding tutorials\ninto working projects instantly."
    draw_marquee.text((80, 240), tagline_text, font=font_m_tagline, fill=secondary_text, spacing=12)
    
    # Draw Badges (Light theme badge styles)
    badge_bg = (241, 245, 249)          # Slate 100
    badge_border = (226, 232, 240)      # Slate 200
    badge_text_color = (51, 65, 85)     # Slate 700
    
    w1 = draw_badge(draw_marquee, 80, 390, "🤖 AI-Powered Extraction", font_m_badge, badge_bg, badge_border, badge_text_color)
    w2 = draw_badge(draw_marquee, 80 + w1 + 15, 390, "📦 Downloadable ZIPs", font_m_badge, badge_bg, badge_border, badge_text_color)
    draw_badge(draw_marquee, 80 + w1 + 15 + w2 + 15, 390, "📚 Step-by-Step Guides", font_m_badge, badge_bg, badge_border, badge_text_color)
    
    # Save Marquee Promo Tile
    marquee_output_path = os.path.join(base_dir, 'chrome-extension', 'promo_marquee.png')
    marquee_tile.save(marquee_output_path, "PNG")
    print(f"✅ Marquee Promo Tile saved to {marquee_output_path}")

if __name__ == "__main__":
    main()
