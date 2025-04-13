@echo off
echo Setting up pet images...

:: Create target directory if it doesn't exist
if not exist "storage\app\public\pet_images" (
  mkdir "storage\app\public\pet_images"
)

:: Copy sample images
xcopy /E /I /Y "storage\samples\pet_images" "storage\app\public\pet_images"

echo Pet images setup complete!
pause