<?php

declare(strict_types = 1);

$includePublic = function(array|string|null $css = null, array|string|null $js = null, ?string $favicon = null): string {
  // path definitions
  $pathDirCSS = PUBLIC_PATH. 'css' .DIRECTORY_SEPARATOR;
  $pathDirJS = PUBLIC_PATH. 'js' .DIRECTORY_SEPARATOR;
  $pathDirFavicon = PUBLIC_PATH. 'favicon' .DIRECTORY_SEPARATOR;

  // checks if exists
  $existsCSS = function($pathFileCSS) use ($pathDirCSS): bool{
    return is_file($pathDirCSS.$pathFileCSS);
  };

  $existsJS = function($pathFileJS) use ($pathDirJS): bool{
    return is_file($pathDirJS.$pathFileJS);
  };

  $existsFavicon = function($pathFileFavicon) use ($pathDirFavicon): bool{
    return is_file($pathDirFavicon.$pathFileFavicon);
  };

  // include string generators
  $includeCSS = fn(string $pathFileCSS, string $attributes = ''): string => $existsCSS($pathFileCSS) ? "<link rel='stylesheet' href='/css/". htmlspecialchars($pathFileCSS, ENT_QUOTES) ."' $attributes>\n" : '';

  $includeJS = fn(string $pathFileJS, string $attributes = ''): string => $existsJS($pathFileJS) ? "<script src='/js/". htmlspecialchars($pathFileJS, ENT_QUOTES) ."' $attributes></script>\n" : '';

  $includeFavicon = fn(string $pathFileFavicon, string $attributes = ''): string => $existsFavicon($pathFileFavicon) ? "<link rel='icon' href='/favicon/". htmlspecialchars($pathFileFavicon, ENT_QUOTES) ."' $attributes>\n" : '';

  $includeAllCSS = function(array $cssFiles) use ($includeCSS): string {
    $accumulatedCSS = '';

    foreach ($cssFiles as $cssFile) {
      $accumulatedCSS .= $includeCSS($cssFile);
    }

    return $accumulatedCSS;
  };

  $includeAllJS = function(array $jsFiles) use ($includeJS): string {
    $accumulatedJS = '';

    foreach ($jsFiles as $jsFile) {
      $accumulatedJS .= $includeJS($jsFile);
    }

    return $accumulatedJS;
  };


  $result = '';
  $cssType = gettype($css);
  $jsType = gettype($js);
  $faviconType = gettype($favicon);

  $result .= match($faviconType) {
    'string' => $includeFavicon($favicon),
    default => '',
  };

  $result .= match($cssType) {
    'string' => $includeCSS($css),
    'array' => $includeAllCSS($css),
    default => '',
  };

  $result .= match($jsType) {
    'string' => $includeJS($js),
    'array' => $includeAllJS($js),
    default => '',
  };

  return $result;
};
