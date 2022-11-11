<?php 
class GoogleJavaScript{
    private const DEFAULT = 1;
    private const BOOTSTRAP = 2;
    private $siteLang = "en";
    private $element = "demo_element2";
    
    public static function printScript($provider, $lng = "en", $element = "demo_element2"){  
        $this->siteLang = $lng;
        $this->element = $element;
         $JSScript = "<script>
        var GTranslator = {
            siteLang: \"{$this->siteLang}\",
            googleElement: \"{$this->element}\",
            OPTION_ACTIVE: false,
            Languages: " . json_encode($this->languages) . ",

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
                new google.translate.TranslateElement({pageLanguage: \"{$this->siteLang}\", autoDisplay: false}, GTranslator.googleElement);
            },
        
            GoogleScript: function(){
                var s1=document.createElement('script');
                s1.async=true;
                s1.type = 'text/javascript';
                s1.src='https://translate.google.com/translate_a/element.js?cb=GTranslator.GoogleInit';
                var s0 = document.getElementsByTagName('script')[0];
                s0.parentNode.insertBefore(s1, s0);
            },
            
            runTranslate: function(from, to) {
                        
                if (GTranslator.Current() == null && lang == from){
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
                if(GTranslator.GButton() != null){";
                if($this->provider == self::DEFAULT){
                    $JSScript .= "GTranslator.GButton().innerHTML = '<img alt=\"' + lang + '\" src=\"{$this->iconPath}' + lang + '{$this->iconType}\"> ' + GTranslator.Languages[lang] + '<span class=\"toggle-cert\"></span>';";
                }else if($this->provider == self::BOOTSTRAP){
                    $JSScript .= "GTranslator.GButton().innerHTML = '<img alt=\"' + lang + '\" src=\"{$this->iconPath}' + GTranslator.Current() + '{$this->iconType}\"> ' + GTranslator.Languages[lang];";
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
                        GTranslator.GButton().classList.toggle('open');
                    },
                
                    Init: function(){
                        GTranslator.GoogleScript(); var wheel = document.getElementById('php-gt-languages');
                            document.getElementsByClassName('toggle-languages')[0].onclick = function(event) {
                                event.preventDefault();
                                GTranslator.toggle();
                                GTranslator.toggleClass();
                            };
                
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
                            });if(GTranslator.Current() != null){
                            document.querySelectorAll('.drop-li').forEach(function(ele, i){
                                 if(GTranslator.GButton() != null && GTranslator.Current() == ele.firstChild.getAttribute('lang')){
                                    GTranslator.GButton().innerHTML = '<img alt=\"' + GTranslator.Current() + '\" src=\"{$this->iconPath}' + GTranslator.Current() + '{$this->iconType}\"> ' + ele.firstChild.textContent + '<span class=\"toggle-cert\"></span>';
                                }
                            });
                        }
                    }
                };";
            }else if($this->provider == self::BOOTSTRAP){
                $JSScript .= "
                    Init: function(){
                        GTranslator.GoogleScript();if(GTranslator.Current() != null){
                            document.querySelectorAll('.drop-li').forEach(function(ele, i){
                                if(GTranslator.GButton() != null && GTranslator.Current() == ele.firstChild.getAttribute('lang')){
                                    GTranslator.GButton().innerHTML = '<img alt=\"' + GTranslator.Current() + '\" src=\"{$this->iconPath}' + GTranslator.Current() + '{$this->iconType}\"> ' + ele.firstChild.textContent;
                                }
                            });
                        }
                    }
                };";
            }

            $JSScript .= "
                (function(){
                    GTranslator.Init();
                })();
            </script>";
        return $JSScript;
    } 
}
