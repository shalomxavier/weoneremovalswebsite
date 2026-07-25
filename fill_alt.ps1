$htmlFiles = Get-ChildItem -Path "c:\Users\jayar\OneDrive\00 Fortyseven Digital\Clients\We One Removals\website\weoneremovals.com" -Filter "*.html"

foreach ($file in $htmlFiles) {
    if ($file.Name -eq "404.html") { continue }
    $content = Get-Content $file.FullName -Raw
    $originalContent = $content
    
    $matches = [regex]::Matches($content, '(?i)<img[^>]*>')
    
    # Process from back to front to not mess up indices
    for ($i = $matches.Count - 1; $i -ge 0; $i--) {
        $match = $matches[$i]
        $tag = $match.Value
        
        $hasEmptyAlt = ($tag -match '(?i)alt\s*=\s*["'']\s*["'']')
        $hasAnyAlt = ($tag -match '(?i)alt\s*=')
        
        if ($hasEmptyAlt -or -not $hasAnyAlt) {
            if ($tag -match '(?i)src\s*=\s*["'']([^"'']+)["'']') {
                $src = $matches[1]
                $filename = [System.IO.Path]::GetFileName($src)
                
                if ($hasEmptyAlt) {
                    $newTag = $tag -replace '(?i)alt\s*=\s*["'']\s*["'']', "alt=`"$filename`""
                } else {
                    $newTag = $tag -replace '>', " alt=`"$filename`">"
                }
                
                $content = $content.Remove($match.Index, $match.Length).Insert($match.Index, $newTag)
            }
        }
    }
    
    if ($content -cne $originalContent) {
        Set-Content -Path $file.FullName -Value $content -Encoding UTF8
        Write-Host "Updated $($file.Name)"
    }
}
