Get-ChildItem -Path 'C:\laragon\www\posyandu-banjar\resources\views' -Filter '*.blade.php' -Recurse | Where-Object { $_.Length -le 5 } | ForEach-Object {
    Write-Host "$($_.Length) bytes - $($_.FullName)"
}
