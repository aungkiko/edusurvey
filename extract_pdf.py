import pdfplumber
from PIL import Image
import sys
import os

sys.stdout.reconfigure(encoding='utf-8')

output_dir = r'd:\xampp\htdocs\edusurvey\pdf_images'
os.makedirs(output_dir, exist_ok=True)

pdf = pdfplumber.open(r'd:\xampp\htdocs\edusurvey\question.pdf')

for i, page in enumerate(pdf.pages):
    # Convert each page to image
    img = page.to_image(resolution=200)
    img_path = os.path.join(output_dir, f'page_{i+1}.png')
    img.save(img_path)
    print(f"Saved page {i+1} to {img_path}")

pdf.close()
print("Done!")
