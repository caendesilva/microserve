<?php

function extractFiles($content): array
{
    $files = [];

    // Split between '<?php // file: tests/*.php' and closing PHP tag
    $lines = explode("\n", $content);
    $fileContent = '';
    $filename = '';

    foreach ($lines as $line) {
        if (preg_match('/^<\?php \/\/ file: (.+)$/', $line, $matches)) {
            if ($filename) {
                $files[$filename] = $fileContent;
            }

            $filename = $matches[1];
            $fileContent = '';
        } else {
            $fileContent .= $line."\n";
        }
    }

    // Save the last file
    if ($filename) {
        $files[$filename] = $fileContent;
    }

    return $files;
}

function saveFiles($files, $outputDir = './'): void
{
    if (! file_exists($outputDir)) {
        mkdir($outputDir, 0777, true);
    }

    foreach ($files as $filename => $content) {
        $filePath = $outputDir.'/'.$filename;
        $dirPath = dirname($filePath);
        $content = '<?php'."\n".$content;
        // Remove closing PHP tag
        $content = preg_replace('/\?>\s*$/', '', $content);
        $content = trim($content)."\n";

        if (! file_exists($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        file_put_contents($filePath, $content);
        echo "Saved: $filePath\n";
    }
}

// Read the input file
$inputFile = 'tests.php';
$content = file_get_contents($inputFile);

// Extract files
$extractedFiles = extractFiles($content);

// Save files
saveFiles($extractedFiles);

echo "File extraction completed.\n";