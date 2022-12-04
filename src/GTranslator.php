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
     * Hold google translator element id name 
     *
     * @var string
    */
    private $element = "google_translate_element2";

    /**
     * Hold additional link class name for language selector options
     *
     * @var string
    */
    private $linkClass;

    /**
     * Hold additional css width value selector container element
     *
     * @var string
    */
    private $selectWidth = "170px";

    /**
     * Hold initial site language
     *
     * @var string
    */
    private $siteLang = "en";

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
        echo '<script>
        if(GTranslator.Current() != "'.$key.'"){
            GTranslator.Translate(null, \'' . $this->siteLang . '|' . ($key??"en") . '\');
        }
        </script>';
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

    /**
     * Sets design style default or bootstrap
     * @param int $prv
     * @return GTranslator $this
    */
    public function setProvider($prv){
        $this->provider = $prv;
        return $this;
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
        $links = '<select onchange="GTranslator.trigger(this)" class="notranslate ' . $this->linkClass . '">';
        foreach($this->languages as $key => $value){
            $links .= '<option value="'.$key.'" lang="'.$key.'" title="'.$value.'">' . $value . '</option>';
        }
        $links .= '</select>';
        $links .= '<div id="'.$this->element.'"></div>';
        return $links;
    }

    /**
     * Builds selector design for default ui
     * @return html|string $html
    */
    private function selectorCustom(){
        $this->setLinkClass("selected-language-item");
        $html =  '<div class="language-selector g-translator-custom">';
        $html .= '<ul class="toggle-translator notranslate">';
        $html .= '<li class="toggle-languages">';
        $html .= '<a class="" href="#" id="php-g-translator">';
        $html .= '<img alt="'.$this->siteLang.'" src="' . $this->iconPath . $this->siteLang . $this->iconType  . '">' . $this->languages[$this->siteLang];
        $html .= '<span class="toggle-cert"></span>';
        $html .= '</a>';
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
        $html .= '<button class="btn btn-outline-gold dropdown-toggle" type="button" id="php-g-translator" data-bs-toggle="dropdown" aria-expanded="false">';
        $html .= '<img alt="' . $this->siteLang . '" src="' . $this->iconPath . $this->siteLang . $this->iconType  . '"> ' . $this->languages[$this->siteLang];
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
     * @return html|string $this->selectorBootstrap() or $this->selectorCustom()
    */
    public function button($width = "170px"){
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
            echo $this->selectorCustom();
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
        $JSScript = "<script>var GTranslator = {siteLang: \"{$this->siteLang}\",googleElement: \"{$this->element}\",OPTION_ACTIVE: !1,Languages: " . json_encode($this->languages) . ",GButton: function(){return document.getElementById('php-g-translator');},Current:function(){var b=document.cookie.match(\"(^|;) ?googtrans=([^;]*)(;|$)\");return b?b[2].split(\"/\")[2]:GTranslator.siteLang},Event:function(b,a){try{if(document.createEventObject){var c=document.createEventObject();b.fireEvent(\"on\"+a,c)}else c=document.createEvent(\"HTMLEvents\"),c.initEvent(a,!0,!0),b.dispatchEvent(c)}catch(d){console.log(\"GTranslator: \"+d)}},runTranslate:function(a,b){if(null!=GTranslator.Current()||lang!=a){for(var c,d=document.getElementsByTagName(\"select\"),e=0;e<d.length;e++)if(/goog-te-combo/.test(d[e].className)){c=d[e];break}null==document.getElementById(GTranslator.googleElement)||0==document.getElementById(GTranslator.googleElement).innerHTML.length||
        0==c.length||0==c.innerHTML.length?setTimeout(function(){GTranslator.runTranslate(a,b)},500):(c.value=b,GTranslator.Event(c,\"change\"))}},GoogleInit:function(){new google.translate.TranslateElement({pageLanguage:\"{$this->siteLang}\",autoDisplay:!1},GTranslator.googleElement)},GoogleScript:function(){var b=document.createElement(\"script\");b.async=!0;b.type=\"text/javascript\";b.src=\"https://translate.google.com/translate_a/element.js?cb=GTranslator.GoogleInit\";var a=document.getElementsByTagName(\"script\")[0];a.parentNode.insertBefore(b,a)},Translate:function(a,b){\"undefined\"!=typeof b&&
            b.value&&(b=b.value);if(!(\"\"==b||1>b.length)){var c=b.split(\"|\"),d=c[1];GTranslator.runTranslate(c[0],d);";
        if($this->provider == self::DEFAULT){
            $JSScript .= "GTranslator.GButton().innerHTML='<img alt=\"'+d+'\" src=\"{$this->iconPath}'+d+'{$this->iconType}\"> '+GTranslator.Languages[d]+'<span class=\"toggle-cert\"></span>'}},";
        }else if($this->provider == self::BOOTSTRAP){
            $JSScript .= "GTranslator.GButton().innerHTML='<img alt=\"'+d+'\" src=\"{$this->iconPath}'+d+'{$this->iconType}\"> '+GTranslator.Languages[d]}},";
        }
        if($this->provider == self::DEFAULT){
            $JSScript .= "toggle:function(){var b=document.getElementById(\"php-gt-languages\");\"none\"===b.style.display?(b.style.display=\"block\",setTimeout(function(){GTranslator.OPTION_ACTIVE=!0},500)):(b.style.display=\"none\",GTranslator.OPTION_ACTIVE=!1)},toggleClass:function(){GTranslator.GButton().classList.toggle(\"open\")},
                Init:function(){GTranslator.GoogleScript();var b=document.getElementById(\"php-gt-languages\");document.getElementsByClassName(\"toggle-languages\")[0].onclick=
                function(a){a.preventDefault();GTranslator.toggle();GTranslator.toggleClass()};b.addEventListener(\"wheel\",function(a){\"block\"===window.getComputedStyle(b).display&&b.scrollTo({top:b.scrollTop-(a.wheelDelta||-a.detail)});return!1});document.querySelectorAll(\"body\").forEach(function(a,c){a.addEventListener(\"click\",function(d){\"block\"===window.getComputedStyle(b).display&&GTranslator.OPTION_ACTIVE&&(GTranslator.toggle(),GTranslator.toggleClass())})});null!=GTranslator.Current()&&
                document.querySelectorAll(\".drop-li\").forEach(function(a,c){GTranslator.Current()==a.firstChild.getAttribute(\"lang\")&&(GTranslator.GButton().innerHTML='<img alt=\"'+GTranslator.Current()+'\" src=\"{$this->iconPath}' + GTranslator.Current() + '{$this->iconType}\"> '+a.firstChild.textContent+'<span class=\"toggle-cert\"></span>')})}};";
        }else if($this->provider == self::BOOTSTRAP){
            $JSScript .= "Init:function(){GTranslator.GoogleScript();null!=GTranslator.Current()&&document.querySelectorAll(\".drop-li\").forEach(function(a,b){GTranslator.Current()==a.firstChild.getAttribute(\"lang\")&&(GTranslator.GButton().innerHTML='<img alt=\"'+GTranslator.Current()+'\" src=\"{$this->iconPath}' + GTranslator.Current() + '{$this->iconType}\"> '+a.firstChild.textContent)})}};";
        }else if($this->provider == self::SELECT){
            $JSScript .= "}},trigger: function(self){GTranslator.Translate(null, '{$this->siteLang}|' + self.value);return false;},Init:function(){GTranslator.GoogleScript();}};";
        }
        $JSScript .= "(function(){GTranslator.Init()})();</script>";
        return  $JSScript;
    }

    /**
     * Returns css style-sheet for design
     * @return string|html|css $styleSheet
    */
    private function addCss(){
        $styleSheet = " <style> body{top:0 !important} #{$this->element}{display:none!important} #goog-gt-tt{display:none !important} .goog-te-banner-frame{display:none !important} .goog-te-menu-value:hover{text-decoration:none !important} .goog-text-highlight{background-color:transparent !important;box-shadow:none !important} #php-g-translator img{height:16px;width:16px}";
        if($this->provider == self::DEFAULT){
            $styleSheet .= ".g-translator-custom{position: relative} .g-translator-custom .toggle-translator{font-family:Arial;font-size:10pt;text-align:left;cursor:pointer;overflow:hidden;width:{$this->selectWidth};line-height:17px;position: absolute;right: 0;list-style-type: none;padding-left: 0px} .g-translator-custom a{text-decoration:none;display:block;font-size:10pt;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box} .g-translator-custom a img{vertical-align:middle;display:inline;border:0;padding:0;margin:0;opacity:0.8} .g-translator-custom a:hover img{opacity:1} .g-translator-custom .toggle-languages{background-color:#FFFFFF; position:relative; z-index:9999; cursor: pointer} .g-translator-custom .toggle-languages a{border:1px solid #CCCCCC;color:#666666;padding:3px 5px} .g-translator-custom .toggle-cert{background-image: url(\"data:image/svg+xml,%3Csvg class='caret-down' width='12' height='8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 1.5l-5 5-5-5' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' stroke='%23000'%3E%3C/path%3E%3C/svg%3E\");background-repeat: no-repeat;background-position: center;padding:3px 5px;width: 12px;position: absolute;right: 0px;top: 0px;bottom: 0px;height: 22px} .g-translator-custom .toggle-languages .open .toggle-cert{-moz-transform: scaleY(-1);-o-transform: scaleY(-1);-webkit-transform: scaleY(-1);transform: scaleY(-1)} .g-translator-custom .language-options{position:relative;border:1px solid #CCCCCC;background-color:#EEEEEE;display:none;width:auto;max-height:300px;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;overflow-y:auto;overflow-x:hidden;z-index: 100;list-style-type: none;padding-left: 0px} .g-translator-custom .language-options li{list-style-type: none} .g-translator-custom .language-options a{background:#eee;color:#000;padding:5px 8px} .g-translator-custom .language-options a:hover{background:#FFC} .g-translator-custom .language-options::-webkit-scrollbar-track{-webkit-box-shadow:inset 0 0 3px rgba(0,0,0,0.3);border-radius:5px;background-color:#F5F5F5} .g-translator-custom .language-options::-webkit-scrollbar{width:5px} .g-translator-custom .language-options::-webkit-scrollbar-thumb{border-radius:5px;-webkit-box-shadow: inset 0 0 3px rgba(0,0,0,.3);background-color:#888} .g-translator-custom #php-g-translator img{margin-right:2px} ";
        }
        if($this->provider == self::SELECT){
            $styleSheet .= ".select-language-item{padding: 6px 12px;}";
        }
        $styleSheet .= "</style>";
        return $styleSheet;
    }
}
