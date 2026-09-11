$ErrorActionPreference = 'Stop'

# Base directories
$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path | Split-Path -Parent

$base = Join-Path $projectRoot 'public/assets/vendor'

# Create directories
$dirs = @(
    "$base/bootstrap/css",
    "$base/bootstrap/js",
    "$base/fontawesome/css",
    "$base/fontawesome/webfonts",
    "$base/jquery",
    "$base/datatables/css",
    "$base/datatables/js",
    "$base/jszip",
    "$base/select2/css",
    "$base/select2/js",
    "$base/sweetalert2"
)
foreach ($d in $dirs) { New-Item -ItemType Directory -Force -Path $d | Out-Null }

function Get-File {
    param(
        [Parameter(Mandatory=$true)][string]$Url,
        [Parameter(Mandatory=$true)][string]$OutFile
    )
    Invoke-WebRequest -Uri $Url -OutFile $OutFile -UseBasicParsing
}

Write-Host "Downloading vendor assets to $base" -ForegroundColor Cyan

# Bootstrap 5.3.0
Get-File -Url 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' -OutFile (Join-Path $base 'bootstrap/css/bootstrap.min.css')
Get-File -Url 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js' -OutFile (Join-Path $base 'bootstrap/js/bootstrap.bundle.min.js')

# Font Awesome 6.4.0
Get-File -Url 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' -OutFile (Join-Path $base 'fontawesome/css/all.min.css')
$faFonts = @('fa-brands-400.woff2','fa-regular-400.woff2','fa-solid-900.woff2','fa-v4compatibility.woff2')
foreach ($f in $faFonts) {
    Get-File -Url ("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/$f") -OutFile (Join-Path $base ("fontawesome/webfonts/$f"))
}

# jQuery 3.6.0
Get-File -Url 'https://code.jquery.com/jquery-3.6.0.min.js' -OutFile (Join-Path $base 'jquery/jquery-3.6.0.min.js')

# DataTables + plugins
Get-File -Url 'https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css' -OutFile (Join-Path $base 'datatables/css/dataTables.bootstrap5.min.css')
Get-File -Url 'https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css' -OutFile (Join-Path $base 'datatables/css/responsive.bootstrap5.min.css')
Get-File -Url 'https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css' -OutFile (Join-Path $base 'datatables/css/buttons.bootstrap5.min.css')

Get-File -Url 'https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js' -OutFile (Join-Path $base 'datatables/js/jquery.dataTables.min.js')
Get-File -Url 'https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js' -OutFile (Join-Path $base 'datatables/js/dataTables.bootstrap5.min.js')
Get-File -Url 'https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js' -OutFile (Join-Path $base 'datatables/js/dataTables.responsive.min.js')
Get-File -Url 'https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js' -OutFile (Join-Path $base 'datatables/js/responsive.bootstrap5.min.js')
Get-File -Url 'https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js' -OutFile (Join-Path $base 'datatables/js/dataTables.buttons.min.js')
Get-File -Url 'https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js' -OutFile (Join-Path $base 'datatables/js/buttons.bootstrap5.min.js')
Get-File -Url 'https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js' -OutFile (Join-Path $base 'datatables/js/buttons.html5.min.js')
Get-File -Url 'https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js' -OutFile (Join-Path $base 'datatables/js/buttons.print.min.js')

# JSZip 3.10.1
Get-File -Url 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js' -OutFile (Join-Path $base 'jszip/jszip.min.js')

# Select2 + theme
Get-File -Url 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css' -OutFile (Join-Path $base 'select2/css/select2.min.css')
Get-File -Url 'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css' -OutFile (Join-Path $base 'select2/css/select2-bootstrap-5-theme.min.css')
Get-File -Url 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js' -OutFile (Join-Path $base 'select2/js/select2.min.js')

# SweetAlert2 11
Get-File -Url 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css' -OutFile (Join-Path $base 'sweetalert2/sweetalert2.min.css')
Get-File -Url 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js' -OutFile (Join-Path $base 'sweetalert2/sweetalert2.min.js')

Write-Host 'Downloads complete' -ForegroundColor Green
