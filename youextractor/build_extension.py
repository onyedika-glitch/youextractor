import os
import zipfile
from PIL import Image

def resize_icon(input_path, output_path, size):
    if not os.path.exists(input_path):
        return False
    try:
        with Image.open(input_path) as img:
            resized_img = img.resize((size, size), Image.Resampling.LANCZOS)
            resized_img.save(output_path, "PNG")
            return True
    except Exception as e:
        print(f"Error resizing icon to {size}x{size}: {e}")
        return False

def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    ext_dir = os.path.join(base_dir, 'chrome-extension')
    zip_path = os.path.join(base_dir, 'chrome-extension.zip')
    
    logo_path = os.path.join(ext_dir, 'logo.jpg')
    
    print("Resizing icons...")
    # Generate the three standard sizes required by the manifest
    resize_icon(logo_path, os.path.join(ext_dir, 'icon16.png'), 16)
    resize_icon(logo_path, os.path.join(ext_dir, 'icon48.png'), 48)
    resize_icon(logo_path, os.path.join(ext_dir, 'icon128.png'), 128)
    print("✅ Icons generated successfully.")
    
    files_to_include = [
        'manifest.json',
        'popup.html',
        'popup.js',
        'background.js',
        'content.js',
        'icon16.png',
        'icon48.png',
        'icon128.png'
    ]
    
    print("\nPackaging extension...")
    try:
        with zipfile.ZipFile(zip_path, 'w', zipfile.ZIP_DEFLATED) as zipf:
            for file_name in files_to_include:
                file_path = os.path.join(ext_dir, file_name)
                if os.path.exists(file_path):
                    zipf.write(file_path, file_name)
                    print(f"  Added {file_name}")
                else:
                    print(f"  ⚠️ Warning: {file_name} not found, skipping.")
        print(f"✅ Extension successfully zipped to {zip_path}")
    except Exception as e:
        print(f"❌ Error zipping extension: {e}")

if __name__ == "__main__":
    main()
