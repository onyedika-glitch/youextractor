import os
from PIL import Image

def resize_image(input_path, output_path, target_size, remove_alpha=False):
    if not os.path.exists(input_path):
        print(f"⚠️ Input file not found: {input_path}")
        return False
    
    try:
        with Image.open(input_path) as img:
            # Convert to RGB if removing alpha (Chrome Web Store screenshots cannot have alpha)
            if remove_alpha and img.mode in ('RGBA', 'LA') or (img.mode == 'P' and 'transparency' in img.info):
                # Create a white background and paste the image over it
                background = Image.new("RGB", img.size, (255, 255, 255))
                background.paste(img, mask=img.split()[3] if img.mode == 'RGBA' else None)
                img = background
            elif remove_alpha:
                img = img.convert("RGB")
            
            # High-quality resize
            resized_img = img.resize(target_size, Image.Resampling.LANCZOS)
            resized_img.save(output_path, "PNG" if output_path.endswith(".png") else "JPEG")
            print(f"✅ Successfully resized {input_path} -> {output_path} ({target_size[0]}x{target_size[1]})")
            return True
    except Exception as e:
        print(f"❌ Error resizing {input_path}: {e}")
        return False

def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    
    # 1. Resize Logo to 128x128
    logo_path = os.path.join(base_dir, 'chrome-extension', 'logo.jpg')
    icon_path = os.path.join(base_dir, 'chrome-extension', 'icon128.png')
    print("Processing Store Icon...")
    resize_image(logo_path, icon_path, (128, 128))
    
    # 2. Resize Screenshots to 1280x800 (and convert to RGB)
    print("\nProcessing Screenshots...")
    for i in range(1, 6):
        screenshot_path = os.path.join(base_dir, 'public', 'img', f'app-screenshot-{i}.png')
        output_screenshot_path = os.path.join(base_dir, 'public', 'img', f'app-screenshot-{i}-compressed.png')
        resize_image(screenshot_path, output_screenshot_path, (1280, 800), remove_alpha=True)

if __name__ == "__main__":
    main()
