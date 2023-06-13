<?php 
/**
 * Cache - A simple php class for google javascript translator
 * @author      Peter Chigozie(NG) peterujah
 * @copyright   Copyright (c), 2022 Peter(NG) peterujah
 * @license     MIT public license
 */
namespace Peterujah\NanoBlock;
class GTranslator{
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
     * @var string
    */
    private $element = "google_translate_element2";

    /**
     * Hold additional link class name for language selector options
     * @var string
    */
    private $linkClass;

    /**
     * Hold button boggle custom class  
     * @var string
    */
    private  $toggleClass;

    /**
     * Hold button type 
     * @var bool
    */
    private $jsTrigger = false;

    /**
     * Hold additional css width value selector container element
     * @var string
    */
    private $selectWidth = "170px";

    /**
     * Hold initial site language
     *
     * @var string
    */
    public $siteLang = "en";

    /**
     * Hold path to country flag icons directory
     *
     * @var string
    */
    private $iconPath;

    /**
     * Hold selected icon type
     *
     * @var string
    */
    private $iconType;
    
     /**
     * Hold ui design provider type
     *
     * @var int
    */
    private $provider;

    /**
     * Hold bootstrap design provider version
     *
     * @var string
    */
    private $bootstrapVersion;

    /**
     * Hold list of languages to  build
     *
     * @var array
    */
    private $languages = array(
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
    );
  
    /**
     * Constructor.
     * @param string  $lang set initial site language
     * @param string $dir set initial icon directory
    */
    public function __construct($lang = "en", $dir = "/"){
        $this->siteLang = $lang;
        $this->setIconPath($dir);
        $this->setIconType(self::PNG);
        $this->setProvider(self::DEFAULT);
    }

    /**
     * Sets array list of languages
     * @param array $list key => value
     * @return GTranslator $this
    */
    public function setLanguages($list){
        $this->languages = $list;
        return $this;
    }

     /**
     * Force page to use language to the languages
     * @param string $key language code iso 2
     * @return GTranslator $this
    */
    public function forceLanguage($key){
        echo '<script> GTranslator.forceLanguage("'.$key.'");</script>';
       return $this;
    }
    
    /**
     * Adds a language to the languages
     * @param string $key language code iso 2
     * @param string $value language country/continent name
     * @return GTranslator $this
    */
    public function addLanguage($key, $value){
        $this->languages[$key] = $value;
        return $this;
    }

    /**
     * get default languages
     * @return string en,us,etc
    */
    public function getLanguageKeys(){
        $jsKeys = array_keys($this->languages);
        return implode(',', $jsKeys);
    }

    /**
     * Sets google element id name
     * @param string $ele 
     * @return GTranslator $this
    */
    public function setGoogleElement($ele){
        $this->element = $ele;
        return $this;
    }

    /**
     * Sets icon extension type
     * @param string $ext 
     * @return GTranslator $this
    */
    public function setIconType($ext){
        $this->iconType = $ext;
        return $this;
    }

    /**
     * Sets icon directory path
     * @param string $path 
     * @return GTranslator $this
    */
    public function setIconPath($path){
        $this->iconPath = $path;
        return $this;
    }

    /**
     * Sets selectors additional link class name
     * @param string $cls
     * @return GTranslator $this
    */
    public function setLinkClass($cls){
        $this->linkClass = $cls;
        return $this;
    }

    public function setToggleClass($cls){
        $this->toggleClass = $cls;
        return $this;
    }


    /**
     * Sets design style default or bootstrap
     * @param int $prv
     * @param int $ver bootstrap version [4/5]
     * @return GTranslator $this
    */
     public function setProvider($prv, $ver = self::BOOTSTRAP_5){
        $this->provider = $prv;
        $this->bootstrapVersion = $ver;
        return $this;
    }

    /**
     * Gets bootstrap attribute name
     * @return String 
    */
    public function getBootstrapAttr(){
        return ($this->bootstrapVersion == self::BOOTSTRAP_5 ? "bs-" : "");
    }

    /**
     * Builds language selector links
     * @param boolean $li if link should be child of list item
     * @return html|string $links
    */
    private function buildLinks($li = false){
        $links = "";
        foreach($this->languages as $key => $value){
            if($li){
                $links .= '<li class="drop-li">';
            }
            $links .= '<a href="#" onclick="GTranslator.Translate(this, \'' . $this->siteLang . '|' . $key . '\');return false;" lang="'.$key.'" title="'.$value.'" class="' . $this->linkClass . '"><img alt="'.$key.'" src="' . $this->iconPath . $key . $this->iconType . '" width="16" height="16"> ' . $value . '</a>';
            if($li){
                $links .= '</li>';
            }
        }
        return $links;
    }

    /**
     * Builds language selector select options
     * @return html|string $html select 
    */
    private function selectOptions(){
        $this->setLinkClass("select-language-item");
        $links = '<select onchange="GTranslator.trigger(this)" class="notranslate php-language-select ' . $this->linkClass . '">';
        foreach($this->languages as $key => $value){
            $links .= '<option value="'.$key.'" lang="'.$key.'" title="'.$value.'">' . $value . '</option>';
        }
        $links .= '</select>';
        $links .= '<div id="'.$this->element.'"></div>';
        return $links;
    }

    /**
     * Builds selector design for default ui
     * @param boolean $jsTrigger if the button is js
     * @return html|string $html
    */
    private function selectorCustom($jsTrigger = false){
        $this->jsTrigger = $jsTrigger;
        $this->setLinkClass("selected-language-item");
        if($jsTrigger){
            $html =  '<div class="language-selector g-translator-custom g-custom-js">';
            $html .= '<a class="open-language-selector" href="#">';
            $html .= '<img alt="'.$this->siteLang.'" src="' . $this->iconPath . $this->siteLang . $this->iconType  . '">';
            $html .= '</a>';
        }else{
            $html =  '<div class="language-selector g-translator-custom">';
        }
        $html .= '<ul class="toggle-translator notranslate ' . $this->toggleClass . '">';
        $html .= '<li class="toggle-languages">';
        if(!$jsTrigger){
            $html .= '<a class="" href="#" id="php-g-translator">';
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
     * Builds selector design for bootstrap ui
     * @return html|string $html
    */
    private function selectorBootstrap(){
        $this->setLinkClass("dropdown-item");
        $html =  '<div class="language-selector">';
        $html .= '<div class="dropdown notranslate">';
        $html .= '<button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" id="php-g-translator" data-' . $this->getBootstrapAttr() . 'toggle="dropdown" aria-expanded="false">';
        $html .= '<img alt="' . $this->siteLang . '" src="' . $this->iconPath . $this->siteLang . $this->iconType  . '"> ' . ($this->languages[$this->siteLang]??$this->siteLang);
        $html .= '</button>';
        $html .= ' <ul id="php-gt-languages" class="dropdown-menu" aria-labelledby="php-g-translator">';
        $html .=  $this->buildLinks(true);
        $html .= '</ul>';
        $html .= '<div id="'.$this->element.'"></div>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    /**
     * Returns computed selector based on provider
     * @param string $width button width
     * @param boolean $jsTrigger if the button is js
     * @return html|string $this->selectorBootstrap() or $this->selectorCustom()
    */
    public function button($width = "170px", $jsTrigger = false){
        $this->jsButton(false, $width = "170px");
    }

    /**
     * Returns computed button based on provider and js
     * @param boolean $jsTrigger if the button is js
     * @param string $width button width
     * @return html|string $this->selectorBootstrap() or $this->selectorCustom()
    */
    public function jsButton($jsTrigger = true, $width = "170px"){
        $this->selectWidth = $width;
        if(empty($this->languages)){
            trigger_error("Error: make sure you add languages first");
            return;
        }
        if($this->provider == self::BOOTSTRAP){
            echo $this->selectorBootstrap();
        }else if($this->provider == self::SELECT){
            echo $this->selectOptions();
        }else{
            echo $this->selectorCustom($jsTrigger);
        }
    }

    /**
     * Renders translator javascript & css engine
     * @return GTranslator $this
    */
    public function load(){
        echo $this->addScript(), $this->addCss();
    }
    
    /**
     * Returns javascript and render google translator engine
     * @return string|html|javascript $JSScript
    */

    public function addScript(){  
        $JSScript = "<script id='php-g-translator-plugin'>var GTranslator = GTranslator || {
            siteLang: \"{$this->siteLang}\",
            googleElement: \"{$this->element}\",
            OPTION_ACTIVE: false,
            Languages: " . json_encode($this->languages) . ",

            forceLanguage: function(key){
                GTranslator.StatusSiteLang = (localStorage.getItem('siteLang')||'{$this->siteLang}');
                GTranslator.StatusChangeLang = parseInt(localStorage.getItem('changeLang')||0);
                if(GTranslator.StatusChangeLang == 0){
                    if(GTranslator.Current() != key && GTranslator.StatusSiteLang != key){
                        GTranslator.Translate(null, '{$this->siteLang}|' + (key||'en'));
                    }
                }
            },
            
            openClose: function(){
                GTranslator.toggle();
                GTranslator.toggleClass();
            },

            GButton: function(){
                return document.getElementById('php-g-translator');
            },

            Current: function() {
                var keyValue = document['cookie'].match('(^|;) ?googtrans=([^;]*)(;|$)'); return keyValue ? keyValue[2].split('/')[2] : GTranslator.siteLang;
            },
            
            Event: function(element,event){
                try{
                    if(document.createEventObject){
                        var evt=document.createEventObject();element.fireEvent('on'+event,evt)
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
                var langKeys = Object.keys(GTranslator.Languages);
                new google.translate.TranslateElement({
                    pageLanguage: \"{$this->siteLang}\", 
                    includedLanguages: '\"' + langKeys.join(',') + '\"',
                    layout: google.translate.TranslateElement.InlineLayout.VERTICAL,
                    autoDisplay: false,
                    additionalOption: {
                        disableAutoTranslation: true,
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
                /*var s0 = document.getElementsByTagName('script')[0];*/
                var s0 = document.getElementById('php-g-translator-plugin');
                s0.parentNode.insertBefore(s1, s0);
            },
            
            runTranslate: function(from, to) {
                        
                if (GTranslator.Current() == null && lang == from){
                    return;
                }
                localStorage.setItem('siteLang', to);
                localStorage.setItem('changeLang', 1);
                var teCombo;
                var sel = document.getElementsByTagName('select');
                for (var i = 0; i < sel.length; i++){
                    if (/goog-te-combo/.test(sel[i].className)) {
                        teCombo = sel[i];
                        break;
                    } 
                }
        
                if (document.getElementById(GTranslator.googleElement) == null || document.getElementById(GTranslator.googleElement).innerHTML.length == 0 || teCombo.length == 0 || teCombo.innerHTML.length == 0) {
                    setTimeout(function() {
                        GTranslator.runTranslate(from, to)
                    }, 500)
                } else {
                    teCombo.value = to;
                    GTranslator.Event(teCombo, 'change');
                }
            },
            
            Translate: function(self, lang_pair) {
                if (typeof lang_pair != 'undefined'){
                    if(lang_pair.value){
                        lang_pair = lang_pair.value;
                    }
                }
        
                if (lang_pair == '' || lang_pair.length < 1){ 
                    return;
                }
        
                var langs = lang_pair.split('|');
                var from = langs[0];
                var lang = langs[1];
                GTranslator.runTranslate(from, lang);
                var canRun = ". ( $this->jsTrigger ? "1" : "(GTranslator.GButton() != null ? 1 : 0)") . ";
                if(canRun){
                    var langImage  = '<img alt=\"' + lang + '\" src=\"{$this->iconPath}' + lang + '{$this->iconType}\">';
                ";
                if($this->provider == self::DEFAULT){
                    if($this->jsTrigger){
                        $JSScript .= "document.getElementsByClassName('open-language-selector')[0].innerHTML = langImage;";
                    }else{
                        $JSScript .= "GTranslator.GButton().innerHTML = langImage + ' ' + GTranslator.Languages[lang] + '<span class=\"toggle-cert\"></span>';";
                    }
                }else if($this->provider == self::BOOTSTRAP){
                    $JSScript .= "GTranslator.GButton().innerHTML = langImage + ' ' + GTranslator.Languages[lang];";
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
                        var canRun = ". ( $this->jsTrigger ? "1" : "(GTranslator.GButton() != null ? 1 : 0)") . ";
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
                };";
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
                };";
            }else if($this->provider == self::SELECT){
                $JSScript .= "}},
                    trigger: function(self){
                        GTranslator.Translate(null, '{$this->siteLang}|' + self.value);
                        localStorage.setItem('siteLang', self.value);
                        localStorage.setItem('changeLang', 1);
                        return false;
                    },
                    Init:function(){
                        GTranslator.GoogleScript();
                        if(null!=GTranslator.Current()){
                            var formObj = document.getElementsByClassName('php-language-select');
                            for(let i = 0, len = formObj.length; i < len; i++){
                                if(formObj[i].value == GTranslator.Current()){
                                    formObj.options[i].selected = true;
                                }
                            }
                        }
                    }
                };";
            }

            $JSScript .= "
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
     * Returns css style-sheet for design
     * @return string|html|css $styleSheet
    */
    private function addCss(){
        $styleSheet = " <style>body.php-google-translator{top:0 !important}.skiptranslate, #{$this->element}, #goog-gt-tt, .goog-te-banner-frame{display:none !important}#php-g-translator img{height:16px;width:16px}";
        if($this->provider == self::DEFAULT){
            $styleSheet .= ".open-language-selector, .g-custom-js{display: inline-block;width:16px;height:16px;}
            .g-translator-custom:not(.g-custom-js){position: relative} 
            .g-translator-custom .toggle-translator{font-family:Arial;font-size:10pt;text-align:left;cursor:pointer;overflow:hidden;width:{$this->selectWidth};line-height:17px;position: absolute;right: 0;list-style-type: none;padding-left: 0px} 
            .g-translator-custom a{text-decoration:none;display:block;font-size:10pt;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box} 
            .g-translator-custom a img{vertical-align:middle;display:inline;border:0;padding:0;margin:0;opacity:0.8} 
            .g-translator-custom a:hover img{opacity:1}
            .g-translator-custom .toggle-languages{background-color:#FFFFFF; position:relative; z-index:9999; cursor: pointer} 
            .g-translator-custom .toggle-languages a{border:1px solid #CCCCCC;color:#666666;padding:3px 5px} 
            .g-translator-custom .toggle-cert{background-image: url(\"data:image/svg+xml,%3Csvg class='caret-down' width='12' height='8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 1.5l-5 5-5-5' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' stroke='%23000'%3E%3C/path%3E%3C/svg%3E\");background-repeat: no-repeat;background-position: center;padding:3px 5px;width: 12px;position: absolute;right: 0px;top: 0px;bottom: 0px;height: 22px} 
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
}
