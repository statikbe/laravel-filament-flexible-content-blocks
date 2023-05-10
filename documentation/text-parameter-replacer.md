# Text Parameter Replacer

## Use it for translations

You can also extend the default Laravel translator with the parameters you have implemented in your 
[TextParameterReplacer](..%2Fsrc%2FReplacer%2FTextParameterReplacer.php). This way you can use all parameters also in 
the translation files.

Below is an example implementation to extend the Laravel Translation class:

```php
<?php

namespace App\Translation;

//TODO change this to your own implementation
use App\Filament\Replacer\YourTextParameterReplacer;
use Illuminate\Support\Facades\App;
use Illuminate\Translation\Translator;

class YourTranslator extends Translator
{
    private ?array $textParameters = null;

    /**
     * @inheritdoc 
     */
    protected function makeReplacements($line, array $replace) {
        if(!$this->textParameters) {
            //TODO change this to your own implementation
            /* @var YourTextParameterReplacer $textParameterReplacer */
            $textParameterReplacer = App::make(YourPassTextParameterReplacer::class);
            $this->textParameters = $textParameterReplacer->getParameters();
        }
        $replace = array_merge($replace, $this->textParameters);

        return parent::makeReplacements($line, $replace);
    }
}
```

You then need to change the default translator singleton in the container by adding this to the `boot()` method of a 
service provider (e.g. `AppServiceProvider`).

```php 
public function boot()
{
    //[...]
 
    //extend translator with text parameters replacer:
    $this->app->singleton('translator', function ($app) {
        /*
         * @see Illuminate\Translation\TranslationServiceProvider
         */
        $loader = $app['translation.loader'];

        $locale = $app->getLocale();

        //TODO change this to your own implementation
        $trans = new YourTranslator($loader, $locale);

        $trans->setFallback($app->getFallbackLocale());

        return $trans;
    });
}
```
