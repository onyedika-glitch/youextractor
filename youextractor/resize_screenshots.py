import os
from PIL import Image

def process_screenshots():
    base_dir = '/home/user/Documents/Youextractor/youextractor/youextractor'
    input_dir = os.path.join(base_dir, 'public', 'img')
    output_dir = os.path.join(base_dir, 'screenshots')
    
    stretched_dir = os.path.join(output_dir, 'stretched')
    cropped_dir = os.path.join(output_dir, 'cropped')
    
    os.makedirs(stretched_dir, exist_ok=True)
    os.makedirs(cropped_dir, exist_ok=True)
    
    target_size = (1280, 800)  # 16:10 aspect ratio
    
    for i in range(1, 6):
        filename = f'app-screenshot-{i}.png'
        input_path = os.path.join(input_dir, filename)
        
        if not os.path.exists(input_path):
            print(f"⚠️ Screenshot {filename} not found in {input_dir}")
            continue
            
        with Image.open(input_path) as img:
            # Ensure RGB (no alpha)
            if img.mode != 'RGB':
                img = img.convert('RGB')
                
            # 1. Stretched (direct resize)
            img_stretched = img.resize(target_size, Image.Resampling.LANCZOS)
            stretched_path = os.path.join(stretched_dir, f'screenshot-{i}-stretched.png')
            img_stretched.save(stretched_path, 'PNG')
            print(f"✅ Created stretched: {stretched_path}")
            
            # 2. Cropped (maintain aspect ratio by cropping sides)
            # Original aspect ratio: 1920/1080 = 1.777...
            # Target aspect ratio: 1280/800 = 1.6
            orig_w, orig_h = img.size
            target_aspect = target_size[0] / target_size[1] # 1.6
            
            # We want to crop from the sides
            # target_w = orig_h * target_aspect = 1080 * 1.6 = 1728
            new_w = int(orig_h * target_aspect)
            left = (orig_w - new_w) // 2
            right = left + new_w
            top = 0
            bottom = orig_h
            
            img_cropped_box = img.crop((left, top, right, bottom))
            img_cropped = img_cropped_box.resize(target_size, Image.Resampling.LANCZOS)
            cropped_path = os.path.join(cropped_dir, f'screenshot-{i}-cropped.png')
            img_cropped.save(cropped_path, 'PNG')
            print(f"✅ Created cropped: {cropped_path}")

if __name__ == '__main__':
    process_screenshots()
