<?php

function createCodeModel($directory, $outputFile): void
{
    // Open the output file in append mode
    $output = fopen($outputFile, 'a');

    // Get all files in the current directory
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    fwrite($output, "--- Below is all code in the $directory directory ---\n");

    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() == 'php') {
            $relativePath = substr($file->getPathname(), strlen($directory) + 1);
            $content = file_get_contents($file->getPathname());
            // Unset the opening PHP tag
            $content = preg_replace('/<\?php/', '', $content, 1);
            $content = trim($content);

            // Write the file path comment and content to the output file
            fwrite($output, "\n<?php // file: $relativePath\n\n");
            fwrite($output, $content . "\n?>\n");
        }
    }

    fclose($output);
}

// Usage
$srcDirectory = './src';
$outputFile = './classes.php';

// Clear the output file if it already exists
file_put_contents($outputFile, '');

// Run the aggregation
createCodeModel($srcDirectory, $outputFile);

echo "PHP files have been aggregated into $outputFile";
