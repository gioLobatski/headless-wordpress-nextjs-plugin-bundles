# =====================================================================
# WordPress Plugin Bundle - ZIP Builder Script
# =====================================================================
# Creates properly structured plugin ZIPs from bundled-plugins/ folder.
#
# Each ZIP will have a single top-level folder matching the plugin slug
# expected by WordPress, e.g.:
#
#   woocommerce.zip
#   |- woocommerce/
#      |- woocommerce.php
#      |- ...
#
# After running this script, upload all ZIPs from plugin-zips/<version>/
# to a new GitHub Release tagged with the same version.
# =====================================================================

param(
    [string]$Version = "v1.3.0"
)

$ErrorActionPreference = "Continue"
$ScriptRoot = $PSScriptRoot
if (-not $ScriptRoot) { $ScriptRoot = Split-Path -Parent $MyInvocation.MyCommand.Path }

$BundledRoot = Join-Path $ScriptRoot "bundled-plugins"
$OutRoot     = Join-Path $ScriptRoot "plugin-zips"
$OutDir      = Join-Path $OutRoot $Version

if (-not (Test-Path -LiteralPath $BundledRoot)) {
    Write-Host "ERROR: bundled-plugins folder not found at $BundledRoot" -ForegroundColor Red
    exit 1
}

# ---------------------------------------------------------------------
# Plugin Map
# ---------------------------------------------------------------------
# Maps the top-level folder name in bundled-plugins/ to the final
# plugin slug (which becomes the ZIP filename and the inner folder name).
# ---------------------------------------------------------------------
$PluginMap = @(
    @{ Source = "advanced-custom-fields-pro (8)";       Slug = "advanced-custom-fields-pro" },
    @{ Source = "classic-editor";                       Slug = "classic-editor" },
    @{ Source = "duplicate-page";                       Slug = "duplicate-page" },
    @{ Source = "gravityforms";                         Slug = "gravityforms" },
    @{ Source = "imagify";                              Slug = "imagify" },
    @{ Source = "ithemes-security-pro";                 Slug = "ithemes-security-pro" },
    @{ Source = "iwp-client";                          Slug = "iwp-client" },
    @{ Source = "seo-by-rank-math";                     Slug = "seo-by-rank-math" },
    @{ Source = "svg-support";                          Slug = "svg-support" },
    @{ Source = "woocommerce.10.7.0";                   Slug = "woocommerce" },
    @{ Source = "wp-graphql";                           Slug = "wp-graphql" },
    @{ Source = "wp-graphql-rank-math-develop (1)";     Slug = "wp-graphql-rank-math" },
    @{ Source = "wp-graphql-tax-query-develop (1)";     Slug = "wp-graphql-tax-query" },
    @{ Source = "wp-graphql-woocommerce-v0.19.0 (2)";   Slug = "wp-graphql-woocommerce" },
    @{ Source = "wp-time-capsule";                      Slug = "wp-time-capsule" },
    @{ Source = "wpgraphql-acf";                        Slug = "wpgraphql-acf" },
    @{ Source = "wpgraphql-ide";                        Slug = "wpgraphql-ide" },
    @{ Source = "wpgraphql-smart-cache";                Slug = "wpgraphql-smart-cache" }
)

# ---------------------------------------------------------------------
# Helpers
# ---------------------------------------------------------------------

function Find-PluginRoot {
    param([string]$Path)

    # A plugin root is a directory containing a *.php file with a
    # "Plugin Name:" header.
    $phpFiles = Get-ChildItem -Path $Path -Filter *.php -File -ErrorAction SilentlyContinue
    foreach ($f in $phpFiles) {
        try {
            $head = Get-Content -Path $f.FullName -TotalCount 40 -ErrorAction Stop
            if ($head -match "Plugin Name:") { return $Path }
        } catch { }
    }

    # If not found here, descend into single subdirectories.
    $subDirs = Get-ChildItem -Path $Path -Directory -ErrorAction SilentlyContinue
    foreach ($d in $subDirs) {
        $found = Find-PluginRoot -Path $d.FullName
        if ($found) { return $found }
    }
    return $null
}

# ---------------------------------------------------------------------
# Build
# ---------------------------------------------------------------------

if (Test-Path -LiteralPath $OutDir) {
    Write-Host "Cleaning existing output directory: $OutDir"
    Remove-Item -LiteralPath $OutDir -Recurse -Force
}
New-Item -ItemType Directory -Path $OutDir -Force | Out-Null

# Use a SHORT staging root to avoid Windows MAX_PATH (260-char) limits.
# OneDrive paths + deep vendor trees easily blow past that.
$staging = "C:\wpb-stg"
if (Test-Path -LiteralPath $staging) {
    Remove-Item -LiteralPath $staging -Recurse -Force -ErrorAction SilentlyContinue
}
New-Item -ItemType Directory -Path $staging -Force | Out-Null

$success = 0
$skipped = 0
$failed  = @()

Write-Host ""
Write-Host "Building plugin ZIPs for $Version" -ForegroundColor Cyan
Write-Host "Output:  $OutDir"
Write-Host "Staging: $staging"
Write-Host ""

foreach ($entry in $PluginMap) {
    $sourceDir = Join-Path $BundledRoot $entry.Source
    $slug      = $entry.Slug
    $zipName   = "$slug.zip"
    $zipPath   = Join-Path $OutDir $zipName

    Write-Host "[ $slug ]" -ForegroundColor Yellow

    if (-not (Test-Path -LiteralPath $sourceDir)) {
        Write-Host "  SKIP  source not found: $($entry.Source)" -ForegroundColor DarkYellow
        $skipped++
        continue
    }

    # Find actual plugin root (handles nested folders).
    $pluginRoot = Find-PluginRoot -Path $sourceDir
    if (-not $pluginRoot) {
        Write-Host "  FAIL  no plugin file (Plugin Name:) found inside $sourceDir" -ForegroundColor Red
        $failed += $slug
        continue
    }

    Write-Host "  src   $pluginRoot"

    # Stage via robocopy to a short path (handles long source paths natively).
    $stageDir = Join-Path $staging $slug
    if (Test-Path -LiteralPath $stageDir) {
        Remove-Item -LiteralPath $stageDir -Recurse -Force -ErrorAction SilentlyContinue
    }
    New-Item -ItemType Directory -Path $stageDir -Force | Out-Null

    $robocopyOut = & robocopy "$pluginRoot" "$stageDir" /E /NFL /NDL /NJH /NJS /NP /R:1 /W:1 2>&1
    # Robocopy exit codes 0-7 are success; >=8 is failure.
    if ($LASTEXITCODE -ge 8) {
        Write-Host "  FAIL  robocopy exit $LASTEXITCODE" -ForegroundColor Red
        Write-Host ($robocopyOut | Out-String)
        $failed += $slug
        Remove-Item -LiteralPath $stageDir -Recurse -Force -ErrorAction SilentlyContinue
        continue
    }

    # Compress staged folder. Source path is short (C:\wpb-stg\<slug>) so
    # Compress-Archive won't hit MAX_PATH.
    try {
        Compress-Archive -Path $stageDir -DestinationPath $zipPath -Force -CompressionLevel Optimal -ErrorAction Stop
        $size   = (Get-Item -LiteralPath $zipPath).Length
        $sizeMb = [math]::Round($size / 1MB, 2)
        Write-Host "  ok    $zipName ($sizeMb MB)" -ForegroundColor Green
        $success++
    } catch {
        Write-Host "  FAIL  Compress-Archive: $($_.Exception.Message)" -ForegroundColor Red
        $failed += $slug
    }

    # Clean stage entry to free disk space between plugins.
    Remove-Item -LiteralPath $stageDir -Recurse -Force -ErrorAction SilentlyContinue
}

# Cleanup staging root.
Remove-Item -LiteralPath $staging -Recurse -Force -ErrorAction SilentlyContinue

# ---------------------------------------------------------------------
# Summary
# ---------------------------------------------------------------------

Write-Host ""
Write-Host "=====================================================" -ForegroundColor Cyan
Write-Host "Build Summary" -ForegroundColor Cyan
Write-Host "=====================================================" -ForegroundColor Cyan
Write-Host ("  Built   : {0}" -f $success) -ForegroundColor Green
Write-Host ("  Skipped : {0}" -f $skipped) -ForegroundColor DarkYellow
Write-Host ("  Failed  : {0}" -f $failed.Count) -ForegroundColor $(if ($failed.Count -gt 0) { "Red" } else { "Gray" })
if ($failed.Count -gt 0) {
    Write-Host "  Failed plugins:" -ForegroundColor Red
    $failed | ForEach-Object { Write-Host "    - $_" -ForegroundColor Red }
}
Write-Host ""
Write-Host "Output directory: $OutDir"
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "  1. Create a new GitHub release tagged $Version"
Write-Host "  2. Upload every .zip from the output directory as a release asset"
Write-Host "  3. Make sure plugin-download-config.php uses version = '$Version'"
Write-Host ""
