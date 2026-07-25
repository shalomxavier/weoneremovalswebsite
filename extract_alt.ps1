$htmlFiles = Get-ChildItem -Path . -Filter *.html
$report = @()

foreach ($file in $htmlFiles) {
    $content = Get-Content $file.FullName -Raw
    # Regex to find img tags and extract src and alt
    $matches = [regex]::Matches($content, '<img\s+[^>]*src="(?<src>[^"]+)"[^>]*alt="(?<alt>[^"]*)"', [System.Text.RegularExpressions.RegexOptions]::IgnoreCase)
    
    foreach ($match in $matches) {
        $report += [PSCustomObject]@{
            File = $file.Name
            Src  = $match.Groups['src'].Value
            Alt  = $match.Groups['alt'].Value
        }
    }
    
    # Also find img tags WITHOUT alt attribute
    $noAltMatches = [regex]::Matches($content, '<img(?![^>]*\balt\b)[^>]*src="(?<src>[^"]+)"', [System.Text.RegularExpressions.RegexOptions]::IgnoreCase)
    foreach ($match in $noAltMatches) {
        $report += [PSCustomObject]@{
            File = $file.Name
            Src  = $match.Groups['src'].Value
            Alt  = "[MISSING]"
        }
    }
}

$report | Export-Csv -Path "alt_extraction.csv" -NoTypeInformation
