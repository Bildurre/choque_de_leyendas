<?php

namespace App\Traits;

use Spatie\Browsershot\Browsershot;

trait ConfiguresBrowsershot
{
  protected function configureBrowsershot(Browsershot $browsershot): Browsershot
  {
    if (config('app.env') === 'production' || config('browsershot.no_sandbox')) {
      $browsershot
        ->setChromePath(config('browsershot.chrome_path', '/usr/bin/google-chrome'))
        ->noSandbox()
        ->setOption('args', [
          '--disable-dev-shm-usage',
          '--disable-gpu',
          '--disable-setuid-sandbox',
          '--no-first-run',
          '--no-zygote',
          '--disable-web-security',
          '--disable-features=VizDisplayCompositor'
        ]);
    }
    
    return $browsershot;
  }
}