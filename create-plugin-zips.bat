@echo off
REM ============================================
REM Plugin Bundle ZIP Creator for GitHub Releases
REM ============================================
REM This script creates properly structured ZIP files
REM for each plugin in the bundled-plugins directory
REM ============================================

echo.
echo ============================================
echo  Plugin Bundle ZIP Creator
echo  For GitHub Releases
echo ============================================
echo.

REM Check if bundled-plugins directory exists
if not exist "bundled-plugins" (
    echo ERROR: bundled-plugins directory not found!
    echo Please run this script from the plugin root directory.
    pause
    exit /b 1
)

REM Create output directory
if not exist "plugin-zips" mkdir plugin-zips
if not exist "plugin-zips\v1.1.0" mkdir plugin-zips\v1.1.0

echo Output directory: plugin-zips\v1.1.0\
echo.

REM Process each plugin directory
for /d %%D in (bundled-plugins\*) do (
    echo.
    echo Processing: %%~nxD
    
    REM Get the first subdirectory (the actual plugin folder)
    for /d %%P in ("%%D\*") do (
        echo   Found plugin folder: %%~nxP
        
        REM Create ZIP with proper structure
        echo   Creating ZIP...
        powershell -Command "Compress-Archive -Path '%%P' -DestinationPath 'plugin-zips\v1.1.0\%%~nxP.zip' -Force"
        
        if exist "plugin-zips\v1.1.0\%%~nxP.zip" (
            echo   SUCCESS: Created %%~nxP.zip
        ) else (
            echo   ERROR: Failed to create %%~nxP.zip
        )
    )
)

echo.
echo ============================================
echo  ZIP Creation Complete!
echo ============================================
echo.
echo Next steps:
echo 1. Go to: https://github.com/gioLobatski/headless-wordpress-nextjs-plugin-bundles/releases
echo 2. Create a new release with tag: v1.1.0
echo 3. Upload all ZIP files from: plugin-zips\v1.1.0\
echo.
pause
