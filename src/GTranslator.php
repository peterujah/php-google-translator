<?php 
/**
 * GTranslator - A simple php class for google javascript translator
 * @author      Peter Chigozie(NG) peterujah
 * @copyright   Copyright (c), 2022 Peter(NG) peterujah
 * @license     MIT public license
 */
namespace Peterujah\NanoBlock;
class GTranslator
{
    /**
     * Hold png image type 
     *
     * @var string
    */
    const PNG = ".png";

    /**
     * Hold svg image type 
     *
     * @var string
    */
    const SVG = ".svg";

    /**
     * Hold type default language selector ui design 
     *
     * @var int
    */
    const DEFAULT = 1;

    /**
     * Hold type bootstrap language selector ui design 
     *
     * @var int
    */
    const BOOTSTRAP = 2;

    /**
     * Hold type select options language selector ui design 
     *
     * @var int
    */
    const SELECT = 3;
    
     /**
     * Hold type bootstrap 4 attribute
     *
     * @var int
    */
    const BOOTSTRAP_4 = 4;

     /**
     * Hold type bootstrap 5 attribute
     *
     * @var int
    */
    const BOOTSTRAP_5 = 5;

    /**
     * Hold google translator element id name 
     * 
     * @var string $element
    */
    private string $element = "google_translate_element2";

    /**
     * Hold additional link class name for language selector options
     * @var string $itemsClass
    */
    private string $itemsClass = '';

     /**
     * Hold button classes 
     * 
     * @var string $buttonClass
    */
    private string $buttonClass = '';

    /**
     * Hold items container custom class  
     * 
     * @var string $containerClass
    */
    private string $containerClass = '';

    /**
     * Hold button type 
     * 
     * @var bool $jsTrigger
    */
    private bool $jsTrigger = false;

    /**
     * Hold additional css width value selector container element
     * 
     * @var string $selectWidth
    */
    private string $selectWidth = "170px";

    /**
     * Hold initial site language
     *
     * @var string
    */
    public string $siteLang = "en";

    /**
     * Hold path to country flag icons directory
     *
     * @var string
    */
    private string $iconPath = '';

    /**
     * Hold selected icon type
     *
     * @var string
    */
    private string $iconType = '';

    /**
     * Hold selected icon type
     *
     * @var string
    */
    private string $certColor = 'currentColor';
    
    /**
     * Hold ui design provider type
     *
     * @var int $provider
    */
    private int $provider;

    /**
     * Hold bootstrap design provider version
     *
     * @var int $bootstrapVersion
    */
    private int $bootstrapVersion = 0;

    /**
     * Hold list of languages to  build
     *
     * @var array<string,string> $languages
    */
    private array $languages = [
        "en" => "English",
        "ar" => "Arabic",
        "fr" => "French",
        "de" => "German",
        "zh-CN" => "Chinese",
        "it" => "Italian",
        "pt" => "Portuguese",
        "es" => "Spanish",
        "ms" => "Malay",
        "ru" => "Russian",
        "nl" => "Dutch",
        "id" => "Indonesian",
        "ja" => "Japanese",
        "ko" => "Korean"
    ];
  
    /**
     * Initialize the translator.
     *
     * @param string $lang Initial site language (ISO 639-1).
     * @param string $base Base path or URL for flag icons.
     */
    public function __construct(string $lang = "en", string $base = "./")
    {
        $this->siteLang = $lang;
        $this->setIconPath($base);
        $this->setIconType(self::PNG);
        $this->setProvider(self::DEFAULT);
    }

    /**
     * Set the full language map.
     *
     * @param array<string,string> $languages Array of language code and name (e.g, `Code => name`).
     * 
     * @return self
     */
    public function setLanguages(array $languages): self
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * Set default select dropdown cert color.
     * 
     * @param string $color The cert color.
     * 
     * @return self
     */
    public function setCertColor(string $color): self
    {
        $this->certColor = urlencode($color ?: 'currentColor');
        return $this;
    }

    /**
     * Enable automatic translation based on browser language.
     *
     * @return self
     */
    public function autoTranslate(): self
    {
        echo '<script>GTranslator.autoTranslate();</script>';

        return $this;
    }

    /**
     * Set the preferred language for auto-translation on load.
     *
     * @param string $key ISO 639-1 language code.
     * 
     * @return self
     */
    public function preferredLanguage(string $key): self
    {
        echo '<script>GTranslator.preferredLanguage("' . $key . '");</script>';

        return $this;
    }

    /**
     * @deprecated This method is deprecated and will be removed in future versions. 
     * 
     * Use preferredLanguage(string $key) instead.
     */
    public function forceLanguage(string $key): self  
    {
        return $this->preferredLanguage($key);
    }

    /**
     * Add a language.
     *
     * @param string $keyISO 639-1 language code.
     * @param string $value Display name.
     * 
     * @return self
     */
    public function addLanguage(string $key, string $value): self 
    {
        $this->languages[$key] = $value;
        return $this;
    }

    /**
     * Get allowed languages, sorted by name.
     * 
     * Ensures English exists and is included in the result.
     *
     * @return array<string,string> Return site allowed languages.
     */
    public function getLanguages(): array
    {
        if(!isset($this->languages["en"])){
            $this->languages["en"] = 'English';
        }

        asort($this->languages);
        return $this->languages;
    }

    /**
     * Set the Google Translate element ID.
     *
     * @param string $ele Element ID.
     * 
     * @return self
     */
    public function setGoogleElement(string $ele): self 
    {
        $this->element = $ele;

        return $this;
    }

    /**
     * Set flag icon file type.
     * 
     * **Constants:**
     * 
     * - GTranslator::PNG
     * - GTranslator::SVG
     *
     * @param string $type File extension (e.g. png, svg).
     * 
     * @return self
     */
    public function setIconType(string $type): self 
    {
        $this->iconType = '.' . ltrim($type, '.');
        return $this;
    }

    /**
     * Set the flag icon directory or base URL.
     *
     * @param string $base Icons base path.
     * 
     * @return self
     */
    public function setIconPath(string $base): self 
    {
        $this->iconPath = rtrim($base, '/') . '/';
        return $this;
    }

    /**
     * Set additional class names for language items.
     *
     * @param string $classes CSS classes.
     * 
     * @return self
     */
    public function setItemsClass(string $classes): self 
    {
        $this->itemsClass = $classes;
        return $this;
    }

    /**
     * Set the container class name.
     *
     * @param string $classes CSS classes.
     * 
     * @return self
     */
    public function setContainerClass(string $classes): self 
    {
        $this->containerClass = $classes;
        return $this;
    }

    /**
     * Set UI provider.
     * 
     * @param int $provider The button style provider
     *           (e.g, GTranslator:BOOTSTRAP, GTranslator:SELECT, GTranslator:DEFAULT).
     * @param int $version Bootstrap version (4 or 5).
     * 
     * @return GTranslator $this
    */
    public function setProvider(int $provider, int $version = self::BOOTSTRAP_5): self 
    {
        $this->provider = $provider;
        $this->bootstrapVersion = $version;

        return $this;
    }

    /**
     * Get Bootstrap attribute prefix.
     *
     * @return string|null "bs-" for v5+, otherwise null.
     */
    public function getBootstrapAttr(): ?string
    {
        return ($this->bootstrapVersion >= self::BOOTSTRAP_5) ? "bs-" : null;
    }

    /**
     * Render the translator button.
     *
     * @param string $width Button width (e.g. 170px).
     * 
     * @return void
     * > This can be called anywhere or wrap in element.
     */
    public function button(string $width = "170px"): void
    {
        $this->jsButton(false, $width);
    }

    /**
     * Ender translator button based on css/element provider type.
     * 
     * @param boolean $jsTrigger If true, it uses a custom trigger button with country flag.
     * @param string $width The button width (default: `170px`).
     * 
     * @return void
     */
    public function jsButton(bool $jsTrigger = true, string $width = "170px"): void
    {
        $this->selectWidth = $width;
        if(empty($this->languages)){
            trigger_error("Error: make sure you add languages first");
            return;
        }

        if($this->provider == self::BOOTSTRAP){
            echo $this->selectorBootstrap();
            return;
        }
        
        if($this->provider == self::SELECT){
            echo $this->selectOptions();
            return;
        }
        
        echo $this->selectorCustom($jsTrigger);
    }

    /**
     * Output the translator assets (JS + CSS).
     * 
     * @return void
     */
    public function load(): void
    {
        echo $this->addScript();
        echo $this->addCss();
    }

    /**
     * Build the Google Translate JavaScript snippet.
     *
     * @return string Returns inline HTML <script> markup.
     * 
     * @see self::load() To print the assets directly.
     */
    public function addScript(): string
    {  
        $JSScript = "<script id='php-g-translator-plugin'>var GTranslator = window.GTranslator || {
            siteLang: \"{$this->siteLang}\",
            googleElement: \"{$this->element}\",
            OPTION_ACTIVE: false,
            Languages: " . json_encode($this->getLanguages()) . ",

            preferredLanguage: function(key){
                var languages = Object.keys(GTranslator.Languages);
                if(GTranslator.Current() == null || GTranslator.Current() != key){
                    var au = GTranslator.getCookie('auto_translated');

                    if (key != GTranslator.siteLang && (au == null || au == 0) && languages.indexOf(key) >= 0) {
                        GTranslator.Translate(null, 'en|' + key, true);
                    }
                }
            },

            autoTranslate: function(){
                var key = navigator.language.toLowerCase() || navigator.userLanguage.toLowerCase();
                switch (key) {
                    case 'zh-cn':
                        var preferred = 'zh-CN';
                        break;
                    case 'zh':
                        var preferred = 'zh-CN';
                        break;
                    case 'zh-tw':
                        var preferred = 'zh-TW';
                        break;
                    case 'zh-hk':
                        var preferred = 'zh-TW';
                        break;
                    case 'he':
                        var preferred = 'iw';
                        break;
                    default:
                        var preferred = key.substr(0, 2);
                        break;
                }
                GTranslator.preferredLanguage(preferred);
            },

            setCookie: function(name, value) {
                var expirationDate = new Date();
                expirationDate.setFullYear(expirationDate.getFullYear() + 5);
                var expires = expirationDate.toUTCString();
                document.cookie = name + '=' + value + '; expires=' + expires + '; path=/;';
            },            

            deleteCookie: function(name){
                var expirationDate = new Date();
                expirationDate.setDate(expirationDate.getDate() - 1);
                var expires = expirationDate.toUTCString();
                document.cookie = name + '=0; expires=' + expires + '; path=/;';
            },

            getCookie: function(name) {
                var cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    var cookie = cookies[i].trim();
                    if (cookie.indexOf(name + '=') === 0) {
                        return cookie.substring(name.length + 1);
                    }
                }
                return null;
            },
            
            openClose: function(){
                GTranslator.toggle();
                GTranslator.toggleClass();
            },

            GButton: function(){
                return document.getElementById('php-g-translator');
            },

            Current: function() {
                var keyValue = document['cookie'].match('(^|;) ? googtrans=([^;]*)(;|$)'); 
                return keyValue 
                    ? keyValue[2].split('/')[2] 
                    : GTranslator.siteLang;
            },
            
            Event: function(element,event){
                try{
                    if(document.createEventObject){
                        var evt=document.createEventObject();
                        element.fireEvent('on'+event,evt)
                    }else{
                        var evt=document.createEvent('HTMLEvents');
                        evt.initEvent(event,true,true);
                        element.dispatchEvent(evt);
                    }
                }catch(e){
                    console.log('GTranslator: ' + e);
                }
            },

            GoogleInit: function() {
                const langs = Object.keys(GTranslator.Languages);
                new google.translate.TranslateElement({
                    pageLanguage: GTranslator.siteLang, 
                    includedLanguages: langs.join(','),
                    autoDisplay: false,
                    layout: google.translate.TranslateElement.InlineLayout.VERTICAL,
                    additionalOption: {
                        disablePoweredBy: true
                    }
                }, GTranslator.googleElement);
            },
        
            GoogleScript: function(){
                var s1 = document.createElement('script');
                s1.async = true;
                s1.defer = true;
                s1.type = 'text/javascript';
                s1.src='https://translate.google.com/translate_a/element.js?cb=GTranslator.GoogleInit';
                var s0 = document.getElementById('php-g-translator-plugin');
                s0.parentNode.insertBefore(s1, s0);
            },
            
            runTranslate: function(from, to) {
                if (GTranslator.Current() == null && to == from){
                    return;
                }
                var teCombo;
                var sel = document.getElementsByTagName('select');
                for (var i = 0; i < sel.length; i++){
                    if (/goog-te-combo/.test(sel[i].className)) {
                        teCombo = sel[i];
                        break;
                    } 
                }

                const ele = document.getElementById(GTranslator.googleElement);

                if (
                    ele == null 
                    || ele.innerHTML.length == 0 
                    || teCombo.length == 0 
                    || teCombo.innerHTML.length == 0
                ) {
                    setTimeout(function() {
                        GTranslator.runTranslate(from, to)
                    }, 500)
                } else {
                    teCombo.value = to;
                    GTranslator.Event(teCombo, 'change');
                }
            },
            
            Translate: function(self, langPair, auto) {
                if (typeof langPair != 'undefined' && langPair.value){
                    langPair = langPair.value;
                }
        
                if (langPair == '' || langPair.length < 1){ 
                    return;
                }
        
                var langs = langPair.split('|');
                var from = langs[0];
                var to = langs[1];

                GTranslator.runTranslate(from, to);
                GTranslator.setCookie('auto_translated', auto ? 1 : 0);

                var canRun = ". ($this->jsTrigger ? "1" : "(GTranslator.GButton() != null ? 1 : 0)") . ";
                if(canRun){
                    var langImage = '<img alt=\"' + to + '\" src=\"{$this->iconPath}' + to + '{$this->iconType}\">';
                ";
                if($this->provider == self::DEFAULT){
                    if($this->jsTrigger){
                        $JSScript .= "document.getElementsByClassName('open-language-selector')[0].innerHTML = langImage;";
                    }else{
                        $JSScript .= "GTranslator.GButton().innerHTML = langImage + ' ' + GTranslator.Languages[to] + '<span class=\"toggle-cert\"></span>';";
                    }
                }else if($this->provider == self::BOOTSTRAP){
                    $JSScript .= "GTranslator.GButton().innerHTML = langImage + ' ' + GTranslator.Languages[to];";
                }
            $JSScript .= "}
            },";
        
            if($this->provider == self::DEFAULT){
                $JSScript .= "
                    toggle: function() {
                        var x = document.getElementById('php-gt-languages');
                        if (x.style.display === 'none') {
                            x.style.display = 'block';
                            setTimeout(function(){
                            GTranslator.OPTION_ACTIVE = true;
                            }, 500);
                        } else {
                            x.style.display = 'none';
                            GTranslator.OPTION_ACTIVE = false;
                        }
                    },
                
                    toggleClass: function() {
                        if(GTranslator.GButton() != null){
                            GTranslator.GButton().classList.toggle('open');
                        }
                    },
                
                    Init: function(){

                        GTranslator.GoogleScript(); 
                        if(typeof document.getElementsByClassName('toggle-languages')[0] != 'undefined'){
                            document.getElementsByClassName('toggle-languages')[0].onclick = function(event) {
                                event.preventDefault();
                                GTranslator.openClose()
                            };
                        }
                        if(typeof document.getElementsByClassName('open-language-selector')[0] != 'undefined'){
                            document.getElementsByClassName('open-language-selector')[0].onclick = function(event) {
                                event.preventDefault();
                                GTranslator.openClose();
                            };
                        }

                        if(wheel = document.getElementById('php-gt-languages')){
                
                            wheel.addEventListener('wheel', function(event){
                                if (window.getComputedStyle(wheel).display === 'block') {
                                    wheel.scrollTo({
                                        top: wheel.scrollTop - (event.wheelDelta || -event.detail)
                                    });
                                }
                                return false;
                            });
                
                            document.querySelectorAll('body').forEach(function(ele, i){
                                ele.addEventListener('click', function(event){
                                    if(window.getComputedStyle(wheel).display === 'block' && GTranslator.OPTION_ACTIVE){
                                        console.log('Is Open');
                                        GTranslator.toggle();
                                        GTranslator.toggleClass();
                                    }
                                });
                            });
                         }
                        var canRun = ". ($this->jsTrigger ? "1" : "(GTranslator.GButton() != null ? 1 : 0)") . ";
                        if(canRun && GTranslator.Current() != null){
                            document.querySelectorAll('.drop-li').forEach(function(ele, i){
                                 if(GTranslator.Current() == ele.firstChild.getAttribute('lang')){
                                    var langImage  = '<img alt=\"' + GTranslator.Current() + '\" src=\"{$this->iconPath}' + GTranslator.Current() + '{$this->iconType}\">';
                                    ";
                                    if($this->jsTrigger){
                                        $JSScript .= "document.getElementsByClassName('open-language-selector')[0].innerHTML = langImage;";
                                    }else{
                                        $JSScript .= "GTranslator.GButton().innerHTML = langImage + ' ' + ele.firstChild.textContent + '<span class=\"toggle-cert\"></span>';";
                                    }
                                    $JSScript .= "
                                 }
                            });
                        }
                    }
                ";
            }else if($this->provider == self::BOOTSTRAP){
                $JSScript .= "
                    Init: function(){
                        GTranslator.GoogleScript();
                        if(GTranslator.Current() != null){
                            document.querySelectorAll('.drop-li').forEach(function(ele, i){
                                if(GTranslator.GButton() != null && GTranslator.Current() == ele.firstChild.getAttribute('lang')){
                                    GTranslator.GButton().innerHTML = '<img alt=\"' + GTranslator.Current() + '\" src=\"{$this->iconPath}' + GTranslator.Current() + '{$this->iconType}\"> ' + ele.firstChild.textContent;
                                }
                            });
                        }
                    }
                ";
            }else if($this->provider == self::SELECT){
                $JSScript .= "
                    trigger: function(self){
                        GTranslator.Translate(null, GTranslator.siteLang + '|' + self.value, false);
                        return false;
                    },
                    Init:function(){
                        GTranslator.GoogleScript();
                        if(null != GTranslator.Current()){
                            var formObj = document.getElementsByClassName('php-language-select')[0];
                            for(let i = 0, len = formObj.length; i < len; i++){
                                if(formObj.options[i].value === GTranslator.Current()){
                                    formObj.options[i].selected = true;
                                }
                            }
                        }
                    }
                ";
            }

            $JSScript .= "
                };
                (function(){
                    window.onload = function() {
                        GTranslator.Init();
                        document.body.classList.add('php-google-translator');
                    };
                })();
            </script>";
        return $JSScript;
    }

    /**
     * Build the translator stylesheet.
     *
     * @return string Return inline CSS markup.
     */
    private function addCss(): string
    {
        $styleSheet = " <style>body.php-google-translator{top:0 !important}.skiptranslate,.VIpgJd-ZVi9od-aZ2wEe-wOHMyf,div[class^='VIpgJd-ZVi9od-aZ2wEe-wOHMyf-'],#{$this->element}, #goog-gt-tt, .goog-te-banner-frame{display:none !important}#php-g-translator img{height:16px;width:16px}";

        if($this->provider == self::DEFAULT){
            $styleSheet .= ".open-language-selector, .g-custom-js{display: inline-block;width:16px;height:16px;}
            .g-translator-custom:not(.g-custom-js){position: relative} 
            .g-translator-custom .toggle-translator{font-family:Arial;font-size:10pt;text-align:left;cursor:pointer;overflow:hidden;width:{$this->selectWidth};line-height:17px;position: absolute;right: 0;list-style-type: none;padding-left: 0px} 
            .g-translator-custom a{text-decoration:none;display:block;font-size:10pt;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box} 
            .g-translator-custom a img{vertical-align:middle;display:inline;border:0;padding:0;margin:0;opacity:0.8} 
            .g-translator-custom a:hover img{opacity:1} 
            .g-translator-custom .toggle-languages{background-color:#FFFFFF; position:relative; z-index:9999; cursor: pointer} 
            .g-translator-custom .toggle-languages a{border:1px solid #CCCCCC;color:#666666;padding:3px 5px} 
            .g-translator-custom .toggle-cert{background-image: url(\"data:image/svg+xml,%3Csvg%20class='caret-down'%20width='12'%20height='8'%20xmlns='http://www.w3.org/2000/svg'%3E%3Cpath%20d='M11%201.5l-5%205-5-5'%20stroke='{$this->certColor}'%20stroke-width='2'%20stroke-linecap='round'%20stroke-linejoin='round'/%3E%3C/svg%3E\");background-repeat: no-repeat;background-position: center;padding:3px 5px;width: 12px;position: absolute;right: 5px;top: 0px;bottom: 0px;height: 22px;margin: auto} 
            .g-translator-custom .toggle-languages .open .toggle-cert{-moz-transform: scaleY(-1);-o-transform: scaleY(-1);-webkit-transform: scaleY(-1);transform: scaleY(-1)} .g-translator-custom .language-options{position:relative;border:1px solid #CCCCCC;background-color:#EEEEEE;display:none;width:auto;max-height:300px;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;overflow-y:auto;overflow-x:hidden;z-index: 100;list-style-type: none;padding-left: 0px} 
            .g-translator-custom .language-options li{list-style-type: none} .g-translator-custom .language-options a{background:#eee;color:#000;padding:5px 8px} .g-translator-custom .language-options a:hover{background:#FFC} .g-translator-custom .language-options::-webkit-scrollbar-track{-webkit-box-shadow:inset 0 0 3px rgba(0,0,0,0.3);border-radius:5px;background-color:#F5F5F5} 
            .g-translator-custom .language-options::-webkit-scrollbar{width:5px} 
            .g-translator-custom .language-options::-webkit-scrollbar-thumb{border-radius:5px;-webkit-box-shadow: inset 0 0 3px rgba(0,0,0,.3);background-color:#888} 
            .g-translator-custom #php-g-translator img{margin-right:2px}";
        }

        if($this->provider == self::SELECT){
            $styleSheet .= ".select-language-item{padding: 6px 12px;}";
        }

        if($this->provider == self::BOOTSTRAP){
            $styleSheet .= ".dropdown-menu{max-height: 300px;overflow: auto;}";
        }
        
        $styleSheet .= "</style>";
        return $styleSheet;
    }

    /**
     * Builds language selector links
     * @param boolean $li if link should be child of list item
     * 
     * @return html|string $links
     */
    private function buildLinks(bool $li = false): string
    {
        $links = "";
        foreach($this->getLanguages() as $key => $value){
            if($li){
                $links .= '<li class="drop-li">';
            }
            $links .= '<a href="#" onclick="GTranslator.Translate(this, \'' . $this->siteLang . '|' . $key . '\', false);return false;" lang="'.$key.'" title="'.$value.'" class="' . $this->itemsClass . '"><img alt="'.$key.'" src="' . $this->iconPath . $key . $this->iconType . '" width="16" height="16"> ' . $value . '</a>';
            if($li){
                $links .= '</li>';
            }
        }
        return $links;
    }

    /**
     * Builds language selector select options
     * 
     * @return html|string $html select 
    */
    private function selectOptions(): string 
    {
        $links = '<select onchange="GTranslator.trigger(this)" class="notranslate php-language-select select-language-item ' . $this->buttonClass . '">';

        foreach($this->getLanguages() as $key => $value){
            $links .= '<option value="'.$key.'" lang="'.$key.'" title="'.$value.'">' . $value . '</option>';
        }

        $links .= '</select>';
        $links .= '<div id="'.$this->element.'"></div>';

        return $links;
    }

    /**
     * Build the default custom UI language selector.
     *
     * Generates the full HTML structure for the non-Bootstrap selector,
     * including the trigger button, language list, and Google container.
     * Designed for progressive enhancement: markup works without JS,
     * while JS toggles visibility and handles language switching.
     *
     * Behavior:
     * - When $jsTrigger is true, renders a minimal trigger button only.
     * - When false, renders a dropdown-style selector with label and caret.
     * - Applies configured icon path/type and CSS class options.
     * - Injects the Google Translate mount element.
     *
     * @param bool $jsTrigger Render JS-trigger-only mode.
     *
     * @return string Return HTML markup for the selector.
     */
    private function selectorCustom(bool $jsTrigger = false): string
    {
        $this->jsTrigger = $jsTrigger;
        $class = 'select-language-item';

        if($this->itemsClass !== ''){
            $class .= ' ' . $this->itemsClass;
        }
        $this->setItemsClass($class);
        if($jsTrigger){
            $html =  '<div class="language-selector g-translator-custom g-custom-js">';
            $html .= '<a class="open-language-selector ' . $this->buttonClass . '" href="#">';
            $html .= '<img alt="'.$this->siteLang.'" src="' . $this->iconPath . $this->siteLang . $this->iconType  . '">';
            $html .= '</a>';
        }else{
            $html =  '<div class="language-selector g-translator-custom">';
        }

        $html .= '<ul class="toggle-translator notranslate ' . $this->containerClass . '">';
        $html .= '<li class="toggle-languages">';

        if(!$jsTrigger){
            $html .= '<a class="' . $this->buttonClass . '" href="#" id="php-g-translator">';
            $html .= '<img alt="'.$this->siteLang.'" src="' . $this->iconPath . $this->siteLang . $this->iconType  . '">' . $this->languages[$this->siteLang];
            $html .= '<span class="toggle-cert"></span>';
            $html .= '</a>';
        }

        $html .= '<ul id="php-gt-languages" class="language-options" style="display:none;">';
        $html .=  $this->buildLinks(true);
        $html .=  '</ul>';
        $html .=  '</li>';
        $html .=  '</ul>';
        $html .= '<div id="'.$this->element.'"></div>';
        $html .=  '</div>';
        
        return $html;
    }

    /**
     * Build the Bootstrap-based language selector UI.
     *
     * Generates a Bootstrap dropdown with the current language and flag
     * as the trigger button, plus a list of available languages.
     * The markup is compatible with Bootstrap 4 and 5 (attribute prefix
     * is resolved via getBootstrapAttr()).
     *
     * Behavior:
     * - Uses Bootstrap dropdown JS for toggle and positioning.
     * - Applies configured button, item, and container class names.
     * - Renders the Google Translate mount element inside the wrapper.
     * - Falls back to the language code if no display name is defined.
     *
     * Dependencies:
     * - Bootstrap CSS and JS must be loaded.
     * - $this->languages must be populated.
     * - $this->siteLang must resolve to a valid icon path.
     *
     * @return string Return HTML markup for the Bootstrap selector.
     */
    private function selectorBootstrap(): string
    {
        $class = 'dropdown-item';

        if($this->itemsClass !== ''){
            $class .= ' ' . $this->itemsClass;
        }

        $this->setItemsClass($class);
        $html =  '<div class="language-selector">';
        $html .= '<div class="dropdown notranslate">';
        $html .= '<button class="btn dropdown-toggle ' . $this->buttonClass . '" type="button" id="php-g-translator" data-' . $this->getBootstrapAttr() . 'toggle="dropdown" aria-expanded="false">';
        $html .= '<img alt="' . $this->siteLang . '" src="' . $this->iconPath . $this->siteLang . $this->iconType  . '"> ' . ($this->languages[$this->siteLang]??$this->siteLang);
        $html .= '</button>';
        $html .= ' <ul id="php-gt-languages" class="dropdown-menu ' . $this->containerClass . '" aria-labelledby="php-g-translator">';
        $html .=  $this->buildLinks(true);
        $html .= '</ul>';
        $html .= '<div id="'.$this->element.'"></div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
