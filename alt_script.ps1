$htmlFiles = Get-ChildItem -Path "c:\Users\jayar\OneDrive\00 Fortyseven Digital\Clients\We One Removals\website\weoneremovals.com" -Filter "*.html"
$totalFiles = $htmlFiles.Count
$imgDict = @{}

foreach ($file in $htmlFiles) {
    if ($file.Name -eq "404.html") { continue }
    $content = Get-Content $file.FullName -Raw
    $imgTags = [regex]::Matches($content, '(?i)<img[^>]*>')
    
    foreach ($tag in $imgTags) {
        $tagText = $tag.Value
        
        $srcMatch = [regex]::Match($tagText, '(?i)src\s*=\s*["'']([^"'']+)["'']')
        $src = if ($srcMatch.Success) { $srcMatch.Groups[1].Value } else { "Unknown" }
        
        $altMatch = [regex]::Match($tagText, '(?i)alt\s*=\s*["'']([^"'']*)["'']')
        if ($altMatch.Success) {
            $altText = $altMatch.Groups[1].Value
            if ([string]::IsNullOrWhiteSpace($altText)) {
                $altVal = "EMPTY"
            } else {
                $altVal = "`"$altText`""
            }
        } else {
            $altVal = "MISSING"
        }
        
        if (-not $imgDict.ContainsKey($src)) {
            $imgDict[$src] = @{
                Alts = @{}
                Pages = @{}
            }
        }
        $imgDict[$src].Alts[$altVal] = 1
        $imgDict[$src].Pages[$file.Name] = 1
    }
}

$output = "## Image Alt Text Report`n`n| Image Source | Formatted Alt Text | Found on Pages |`n| --- | --- | --- |`n"

foreach ($key in $imgDict.Keys | Sort-Object) {
    $alts = ($imgDict[$key].Alts.Keys) -join ", "
    $pageCount = $imgDict[$key].Pages.Count
    if ($pageCount -ge 17) {
        $pagesList = "*All Pages*"
    } elseif ($pageCount -ge 10) {
        $pagesList = "*Most Pages ($pageCount)*"
    } else {
        $sortedPages = ($imgDict[$key].Pages.Keys | Sort-Object) -join ", "
        $pagesList = $sortedPages
    }
    
    $output += "| $($key.Replace('\', '/')) | $alts | $pagesList |`n"
}

Set-Content -Path "c:\Users\jayar\OneDrive\00 Fortyseven Digital\Clients\We One Removals\website\weoneremovals.com\alt_report_v2.md" -Value $output
