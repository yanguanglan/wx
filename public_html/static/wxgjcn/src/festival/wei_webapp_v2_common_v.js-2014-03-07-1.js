var _env=(function(){var f=navigator.userAgent,b=null,c=function(h,i){var g=h.split(i);g=g.shift()+"."+g.join("");return g*1},d={ua:f,version:null,ios:false,android:false,windows:false,blackberry:false,meizu:false,weixin:false,wVersion:null,touchSupport:("createTouch" in document),hashSupport:!!("onhashchange" in window)};b=f.match(/MicroMessenger\/([\.0-9]+)/);if(b!=null){d.weixin=true;d.wVersion=c(b[1],".")}b=f.match(/Android(\s|\/)([\.0-9]+)/);if(b!=null){d.android=true;d.version=c(b[2],".");d.meizu=/M030|M031|M032|MEIZU/.test(f);return d}b=f.match(/i(Pod|Pad|Phone)\;.*\sOS\s([\_0-9]+)/);if(b!=null){d.ios=true;d.version=c(b[2],"_");return d}b=f.match(/Windows\sPhone\sOS\s([\.0-9]+)/);if(b!=null){d.windows=true;d.version=c(b[1],".");return d}var e={a:f.match(/\(BB1\d+\;\s.*\sVersion\/([\.0-9]+)\s/),b:f.match(/\(BlackBerry\;\s.*\sVersion\/([\.0-9]+)\s/),c:f.match(/^BlackBerry\d+\/([\.0-9]+)\s/),d:f.match(/\(PlayBook\;\s.*\sVersion\/([\.0-9]+)\s/)};for(var a in e){if(e[a]!=null){b=e[a];d.blackberry=true;d.version=c(b[1],".");return d}}return d}()),_ua=_env.ua,_touchSupport=_env.ios||_env.android||_env.touchSupport,_hashSupport=_env.hashSupport,_isIOS=_env.ios,_isOldIOS=_env.ios&&(_env.version<4.5),_isAndroid=_env.android,_isMeizu=_env.meizu,_isOldAndroid22=_env.android&&(_env.version<2.3),_isOldAndroid23=_env.android&&(_env.version<2.4),_clkEvtType=_touchSupport?"touchstart":"click",_movestartEvt=_touchSupport?"touchstart":"mousedown",_moveEvt=_touchSupport?"touchmove":"mousemove",_moveendEvt=_touchSupport?"touchend":"mouseup",_vendor=(/webkit/i).test(navigator.appVersion)?"webkit":(/firefox/i).test(navigator.userAgent)?"Moz":"opera" in window?"O":(/MSIE/i).test(navigator.userAgent)?"ms":"",_has3d="WebKitCSSMatrix" in window&&"m11" in new WebKitCSSMatrix(),_trnOpen="translate"+(_has3d?"3d(":"("),_trnClose=_has3d?",0)":")",_needHistory=(_isIOS&&!!(window.history&&window.history.pushState)),_appCache=window.applicationCache,_doAjax=function(b,a,c,i,h){if(typeof a=="undefined"){a="POST"}if(typeof c=="undefined"){c=null}if(typeof h=="undefined"){h=true}a=a.toLowerCase();var e=null,g=[];if(window.ActiveXObject){e=new ActiveXObject("Microsoft.XMLHTTP")}else{if(window.XMLHttpRequest){e=new XMLHttpRequest()}else{return false}}e.onreadystatechange=function(l){if(e.readyState==4){if(e.status==200||e.status==0){var k=e.responseText;var j=h?JSON.parse(k):k;if(i){i.call(null,j)}}}};if(c){for(var d in c){g.push(d+"="+c[d])}}if(!g.length){g=null}else{g=g.join("&")}if(a=="get"&&g!=null){if(b.indexOf("?")>-1){b+="&"}else{b+="?"}b+=g;g=null}if(console&&console.log){console.log("ajax: ",b,g)}try{e.open(a,b,true);if(a=="post"){e.setRequestHeader("content-type","application/x-www-form-urlencoded")}e.setRequestHeader("X-Requested-With","XMLHttpRequest");e.send(g)}catch(f){throw"[ajax] request error"}return true},_q=function(c,b){if(b&&typeof b==="string"){try{b=_q(b)}catch(a){console.log(a);return}}return(b||document).querySelector(c)},_qAll=function(c,b){if(b&&typeof b==="string"){try{b=_q(b)}catch(a){console.log(a);return}}return(b||document).querySelectorAll(c)},_qConcat=function(){var d=0,c=arguments.length,b=[];for(;d<c;d++){var a=arguments[d];if(typeof a==="string"){a=_qAll(a)}else{if("nodeType" in a&&a.nodeType===1){a=[a]}}_forEach(a,function(e){b.push(e)})}return b},MCache=(function(){var a={};return{set:function(b,c){a[b]=c},get:function(b){return a[b]},clear:function(){a={}},remove:function(b){delete a[b]}}}()),MStorage=(function(){var e=window.sessionStorage,h=window.localStorage,c=function(i){var j=g(i);if(j!=null){return j.value}return null},g=function(i){if(i in e){return JSON.parse(e.getItem(i))}else{if(i in h){return JSON.parse(h.getItem(i))}else{return null}}},a=function(j,i){var l={value:i,ts:(new Date).getTime()};l=JSON.stringify(l);e.setItem(j,l);h.setItem(j,l)},d=function(){e.clear();h.clear()},b=function(i){e.removeItem(i);h.removeItem(i)},f=function(l){var i=(new Date).getTime(),k;for(var j in h){k=MStorage.getData(j);if(i-k.ts>l){h.removeItem(j);e.removeItem(j)}}};return{set:a,get:c,getData:g,clear:d,remove:b,removeExpires:f}}()),MURLHash=(function(){function c(h,i){var f=encodeURIComponent,e,g=[];var j=i?i:"&";for(e in h){g.push(f(e)+"="+f(h[e]))}return g.join(j)}function a(e,f){var d=e.indexOf(f);return d==-1?[e,""]:[e.substring(0,d),e.substring(d+1)]}var b=function(d,m,j){var k=d||window.location.href;var r=j||"&";var q=a(k,m||"#");var l=q[0];var p=q[1];this.map={};this.sign=r;if(p){var g=p.split(r);for(var f=0;f<g.length;f++){var n=g[f];var e=a(n,"=");this.map[e[0]]=e[1]}}this.size=function(){return this.keys().length};this.keys=function(){var i=[];for(var h in this.map){if(h!="_hashfoo_"){i.push(h)}}return i};this.values=function(){var i=[];for(var h in this.map){if(h!="_hashfoo_"){i.push(this.map[h])}}return i};this.put("_hashfoo_",Math.random())};b.prototype.get=function(d){return this.map[d]||null};b.prototype.put=function(d,e){this.map[d]=e};b.prototype.set=b.prototype.put;b.prototype.putAll=function(d){if(typeof(d)=="object"){for(var e in d){this.map[e]=d[e]}}};b.prototype.remove=function(e){if(this.map[e]){var d={};for(var f in this.map){if(f!=e){d[f]=this.map[f]}}this.map=d}};b.prototype.toString=function(){var e={};for(var d in this.map){if(d!="_hashfoo_"){e[d]=this.map[d]}}return c(e,"&")};b.prototype.clone=function(){return new b("foo#"+this.toString(),this.sign)};return b}()),MData=(function(){function b(f){var e=new RegExp("\\-([a-z])","g");if(!e.test(f)){return f}return f.toLowerCase().replace(e,RegExp.$1.toUpperCase())}function d(e){return e.replace(/([A-Z])/g,"-$1").toLowerCase()}function c(g,f,e){g.setAttribute("data-"+d(f),e)}function a(g,f){var e=g.getAttribute("data-"+d(f));return e||undefined}return function(h,f,e){if(arguments.length>2){try{h.dataset[b(f)]=e}catch(g){c(h,f,e)}}else{try{return h.dataset[b(f)]}catch(g){return a(h,f)}}}}()),MDialog=(function(){var e="javascript:void(0)";var c=function(m){return(typeof m=="undefined")};var g=function(){var o='<div class="mModal"><a href="'+e+'"></a></div>';_q("body").insertAdjacentHTML("beforeEnd",o);o=null;var n=_q(".mModal:last-of-type");if(_qAll(".mModal").length>1){n.style.opacity=0.01}_q("a",n).style.height=window.innerHeight+"px";n.style.zIndex=window._dlgBaseDepth++;return n};var h=function(){if(c(window._dlgBaseDepth)){window._dlgBaseDepth=900}};var k=function(m){if(c(m)){m=true}_q("body").style.overflow=m?"hidden":"visible"};var i=function(P,G,K,w,L,H,A,x,M,z,Q,o,p){if(c(G)){G=null}if(c(K)){K=null}if(c(L)){L=null}if(c(H)){H=null}if(c(A)){A=null}if(c(x)){x=null}if(c(M)){M=null}if(c(z)){z=null}if(c(Q)){Q=true}if(c(o)){o=true}h();var E=window.innerWidth,N=window.innerHeight,y=null,F=null;if(o){F=g()}y='<div class="mDialog"><figure></figure><h1></h1><h2></h2><h3></h3><footer>	<a class="two"></a>	<a class="two"></a>	<a class="one"></a></footer><a class="x">X</a></div>';_q("body").insertAdjacentHTML("beforeEnd",y);y=null;var J=_q("div.mDialog:last-of-type",_q("body")),C=_q("figure",J),s=_q("httpsrc/festival/footer a.one",J),r=_q("footer a.two:nth-of-type(1)",J),q=_q("footer a.two:nth-of-type(2)",J),I=_q("a.x",J),B=0,O=[],v=function(R,m,t){R.addEventListener(m,t);O.push({o:R,e:m,f:t})},n=function(m,t){var R=_q(m,J);if(t!=null){R.innerHTML=t}else{R.parentNode.removeChild(R)}return R},D=function(t){if(typeof p==="function"){p()}while(O.length){var m=O.shift();m.o.removeEventListener(m.e,m.f)}J.parentNode.removeChild(J);window._dlgBaseDepth--;if(F==null){return}F.parentNode.removeChild(F);window._dlgBaseDepth--;k(false)};var u=n("h1",P);if(u.clientHeight>51){u.style.textAlign="left"}n("h2",G);n("h3",K);if(z==null){J.removeChild(C)}else{_addClass(C,z)}C=null;if(A==null){r.parentNode.removeChild(r);q.parentNode.removeChild(q);r=null;q=null;s.innerHTML=w;s.href=e;if(H!=null){_addClass(s,H)}if(L!=null){v(s,"click",L)}v(s,"click",D)}else{s.parentNode.removeChild(s);s=null;r.innerHTML=w;r.href=e;if(H!=null){_addClass(r,H)}if(L!=null){v(r,"click",L)}v(r,"click",D);q.innerHTML=A;q.href=e;if(M!=null){_addClass(q,M)}if(x!=null){v(q,"click",x)}v(q,"click",D)}if(Q){I.href=e;v(I,"click",D)}else{I.parentNode.removeChild(I);I=null}if(F!=null){v(F,"click",D)}J.style.zIndex=window._dlgBaseDepth++;J.style.left=0.5*(E-J.clientWidth)+"px";B=parseInt(0.45*(N-J.clientHeight));J.style.top=B+"px";MData(J,"ffixTop",B);if(o){k()}return J};var j=function(s,q,t,r,p,u,n,m,o){return i(s,q,t,r,p,u,null,null,null,n,m,o)};var f=function(v,o,q){if(c(o)){o=null}if(c(q)){q=3000}var r='<div class="mNotice">	<span></span></div>';_q("body").insertAdjacentHTML("beforeEnd",r);h();var n=_q("div.mNotice:last-of-type",_q("body")),m=_q("span",n),s=window.innerWidth,p=window.innerHeight,u=0;m.innerHTML=v;if(o!=null){_addClass(m,o)}n.style.zIndex=window._dlgBaseDepth++;n.style.left=0.5*(s-n.clientWidth)+"px";u=parseInt(0.45*(p-n.clientHeight));n.style.top=u+"px";MData(n,"ffixTop",u);_setTimeout(function(){n.parentNode.removeChild(n);window._dlgBaseDepth--},q);return n};var b=function(u,D,H,n){if(c(D)){D=295}if(c(H)){H=true}if(c(n)){n=true}h();var y=window.innerWidth,E=window.innerHeight,s=null,A=null;if(n){A=g()}s='<div class="mImgPopup"><figure></figure><a class="x">X</a></div>';_q("body").insertAdjacentHTML("beforeEnd",s);var z=_q("div.mImgPopup:last-of-type",_q("body")),w=_q("figure",z),B=_q("span",z),C=_q("a.x",z),y=window.innerWidth,E=window.innerHeight,v=0,F=[],r=function(t,m,p){t.addEventListener(m,p);F.push({o:t,e:m,f:p})},x=function(p){while(F.length){var m=F.shift();m.o.removeEventListener(m.e,m.f)}z.parentNode.removeChild(z);window._dlgBaseDepth--;if(A==null){return}A.parentNode.removeChild(A);window._dlgBaseDepth--;k(false)},o=function(J){var p=J.currentTarget,m=p.width,t=p.height,I=1;w.appendChild(p);if(m>D){I=m/D}w.style.height=z.style.height=p.style.height=parseInt(t/I)+"px";w.style.width=z.style.width=p.style.width=parseInt(m/I)+"px";q()},q=function(){z.style.zIndex=window._dlgBaseDepth++;z.style.left=0.5*(y-z.clientWidth)+"px";v=0.5*(E-z.clientHeight);z.style.top=v+"px";MData(z,"ffixTop",v);if(n){k()}};q();if(H){C.href=e;r(C,"click",x)}else{C.parentNode.removeChild(C);C=null}if(A!=null){r(A,"click",x)}var G=new Image;r(G,"load",o);G.src=u;return z};var l=function(r,t){if(_q("#mLoading")){return}if(c(r)){r=""}if(c(t)){t=false}h();var q=window.innerWidth,s=window.innerHeight,p=null,n=null;if(t){n=g();n.id="mLoadingModal"}p='<div id="mLoading"><div class="lbk"></div><div class="lcont">'+r+"</div></div>";_q("body").insertAdjacentHTML("beforeEnd",p);var o=_q("#mLoading");o.style.top=(_q("body").scrollTop+0.5*(s-o.clientHeight))+"px";o.style.left=0.5*(q-o.clientWidth)+"px";return o};var d=function(v,o,n,s){if(c(v)){v=null}if(c(o)){o=true}if(c(n)){n=null}if(c(s)){s=true}h();var z=window.innerWidth,r=window.innerHeight,y=null,p=null;if(s){p=g()}y='<div class="mDialog freeSet">'+v+'<a class="x">X</a></div>';_q("body").insertAdjacentHTML("beforeEnd",y);y=null;var x=_q("div.mDialog:last-of-type",_q("body")),w=_q("a.x",x),B=0,u=[],q=function(C,m,t){C.addEventListener(m,t);u.push({o:C,e:m,f:t})},A=function(t){if(n!=null){(n)()}while(u.length){var m=u.shift();m.o.removeEventListener(m.e,m.f)}x.parentNode.removeChild(x);window._dlgBaseDepth--;if(p==null){return}p.parentNode.removeChild(p);window._dlgBaseDepth--;k(false)};if(o){w.href=e;q(w,"click",A)}else{w.parentNode.removeChild(w);w=null}if(p!=null){q(p,"click",A)}x.style.zIndex=window._dlgBaseDepth++;x.style.left=0.5*(z-x.clientWidth)+"px";B=parseInt(0.45*(r-x.clientHeight));x.style.top=B+"px";MData(x,"ffixTop",B);if(s){k()}return x};var a={ICON_TYPE_SUCC:"succ",ICON_TYPE_WARN:"warn",ICON_TYPE_FAIL:"fail",BUTTON_STYLE_ON:"on",BUTTON_STYLE_OFF:"off",confirm:i,alert:j,notice:f,popupImage:b,showLoading:l,popupCustom:d};return a}()),MLoading={show:MDialog.showLoading,hide:function(){var b=_q("#mLoading");if(b){b.parentNode.removeChild(b)}var a=_q("#mLoadingModal");if(a){a.parentNode.removeChild(a)}}},_checkOffline=function(){var a=!!_appCache;if(!a){return}_appCache.addEventListener("updateready",function(c){if(_appCache.status==_appCache.UPDATEREADY){try{_appCache.swapCache()}catch(b){}location.href=location.origin+location.pathname+"?rnd="+Math.random()+location.hash}},false)},_html5FixForOldEnv=function(){var a="abbr,article,aside,audio,canvas,datalist,details,dialog,eventsource,fieldset,figure,figcaption,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,small,time,video,legend";a.split(",").forEach(function(d,c,b){document.createElement(d)});_writeCSS(a+"{display:block;}")},_writeCSS=function(b){var c=document.createElement("style");c.innerHTML=b;try{_q("head").appendChild(c)}catch(a){}},_testFixedSupport=function(){var c=document.createElement("div"),b=document.createElement("div"),a=true;c.style.position="absolute";c.style.top="200px";b.style.position="fixed";b.style.top="100px";c.appendChild(b);document.body.appendChild(c);if(b.getBoundingClientRect&&b.getBoundingClientRect().top==c.getBoundingClientRect().top){a=false}document.body.removeChild(c);return a},_fixedStyleHook=function(a){if(!_q(".footFix")){return}if(typeof a=="undefined"){a=true}var b=("_needFixedStyle" in window)||(_env.ios&&_env.version<4.5)||(_env.android&&_env.version<3)||_env.meizu||(_env.blackberry&&_env.version<7)||!_testFixedSupport();if(b){if(a){_hook1TO=window.setTimeout(_fixedStyleHelper,200);window.addEventListener("scroll",_fixedStyleHelper);window.addEventListener("orientationchange",_fixedStyleHelper);window.addEventListener("touchmove",_fixedStyleHelper);window.addEventListener("touchend",_fixedStyleHelper)}else{window.clearTimeout(_hook1TO);window.removeEventListener("scroll",_fixedStyleHelper);window.removeEventListener("orientationchange",_fixedStyleHelper);window.removeEventListener("touchmove",_fixedStyleHelper);window.removeEventListener("touchend",_fixedStyleHelper)}}},_fixedStyleHelper=function(a){_forEach(_qAll(".footFix"),function(f){var h=f,d=window.innerHeight,c=window.scrollY,i=MData(h,"ffixTop"),g=MData(h,"ffixBottom"),b;if(h){try{b=h.clientHeight;h.style.position="absolute";if(i){h.style.top=c+i*1+"px"}else{if(g){h.style.top=c+d-g*1-b+"px"}else{h.style.top=c+d-b+"px"}}h.style.bottom="auto"}catch(e){}}})},_trim=function(a){return a.replace(/(^\s+|\s+$)/g,"")},_removeClass=function(d,c){if(typeof d==="string"){try{d=_q(d)}catch(a){console.log(a);return}}var b=new RegExp("(^|\\s)+("+c+")(\\s|$)+","g");try{d.className=d.className.replace(b,"$1$3")}catch(a){}b=null},_addClass=function(c,b){if(typeof c==="string"){try{c=_q(c)}catch(a){console.log(a);return}}_removeClass(c,b);c.className=_trim(c.className+" "+b)},_getRealStyle=function(a,c){if(!a||!c){return}var d="";try{d=(typeof(window.getComputedStyle)=="undefined")?a.currentStyle[c]:window.getComputedStyle(a,null)[c]}catch(b){d=a.style[c]}return d.replace(/px$/,"")},_forEach=function(a,c){if(typeof a==="string"){try{a=_qAll(a)}catch(b){console.log(b);return}}Array.prototype.forEach.call(a,c)},_show=function(){var d=0,b=arguments.length,e;for(;d<b;d++){e=arguments[d];if(typeof e==="string"){try{e=_q(e)}catch(c){console.log(c);return}}if(e.nodeType!=undefined&&e.nodeType==1){e.style.display="";e.removeAttribute("hidden")}else{if(e.hasOwnProperty("length")){try{var a=[];_forEach(e,function(g,f,h){a.push(g)});_show.apply(null,a)}catch(c){}}}}},_hide=function(){var d=0,b=arguments.length,e;for(;d<b;d++){e=arguments[d];if(typeof e==="string"){try{e=_q(e)}catch(c){console.log(c);return}}if(e&&e.nodeType!=undefined&&e.nodeType==1){e.style.display="none"}else{if(e&&e.hasOwnProperty("length")){try{var a=[];_forEach(e,function(g,f,h){a.push(g)});_hide.apply(null,a)}catch(c){}}}}},_setTimeout=function(){var b=arguments[0],c=arguments[1],a=Array.prototype.slice.call(arguments,2);return window.setTimeout(function(d){return function(){b.apply(null,d)}}(a),c)},_onPageLoaded=function(a){window.addEventListener("DOMContentLoaded",a)},_preventWXPageScroll=function(){var b,a=0;document.addEventListener("touchstart",function(c){b=c.touches[0].screenX;a=c.touches[0].screenY});document.addEventListener("touchmove",function(c){var d=Math.abs(c.touches[0].screenX-b);var f=Math.abs(c.touches[0].screenY-a);if((d*3)>f){c.preventDefault()}},false)},_pageToTop=function(){_q("body").scrollTop=-1;window.scrollTo(0,-1)},_delegate=function(){var a=arguments[0],c=arguments[1],b=Array.prototype.slice.call(arguments,2);if(b.length==1&&b[0] instanceof Array){b=b[0]}return function(){var d=[],f=0,e=arguments.length;for(;f<e;f++){d[f]=arguments[f]}d=d.concat(b);a.apply(c,d)}},_fakeClick=function(b){_q("body").insertAdjacentHTML("beforeEnd",'<a href="javascript:void(0)" id="fakeClick" style="opacity:.01"></a>');var c=_q("#fakeClick"),a;c.addEventListener("click",function(d){d.preventDefault();b()});if(document.createEvent){a=document.createEvent("MouseEvents");if(a.initMouseEvent){a.initMouseEvent("click",true,true,window,0,0,0,0,0,false,false,false,false,0,null);c.dispatchEvent(a)}}c.parentNode.removeChild(c)},_hideKeyboard=function(){if("activeElement" in document){document.activeElement.blur()}window.focus()},console=window.console||{log:function(){}};(function(){var a=function(b){b.preventDefault()};window._disableSafariElastic=function(){document.addEventListener("touchmove",a,false)};window._enableSafariElastic=function(){document.removeEventListener("touchmove",a,false)}}());_checkOffline();_html5FixForOldEnv();_preventWXPageScroll();if(/iPad/.test(_ua)&&_env.ios&&_env.version>=7){_onPageLoaded(function(){_q("body").style.width=window.innerWidth+"px";window.addEventListener("orientationchange",function(a){_q("body").style.width=window.innerWidth+"px"},false)})}if(location.href.indexOf("httpsrc/festival/qq.com")>-1){window.onerror=function(){return true}};