!function(e){var t={};function r(n){if(t[n])return t[n].exports;var i=t[n]={i:n,l:!1,exports:{}};return e[n].call(i.exports,i,i.exports,r),i.l=!0,i.exports}r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)r.d(n,i,function(t){return e[t]}.bind(null,i));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="/",r(r.s=1)}({1:function(e,t,r){e.exports=r("LTib")},LTib:function(e,t){var r,n;r=function(e){return"email"==e.target.type?/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(e.target.value)?void e.target.classList.add("is-valid"):void e.target.classList.add("is-invalid"):"password"==e.target.type?e.target.value.length<6?void e.target.classList.add("is-invalid"):void e.target.classList.add("is-valid"):void 0},n=function(e){e.target.classList.remove("is-invalid"),e.target.classList.remove("is-valid")},window.onload=function(){for(var e=document.querySelectorAll("input[required]"),t=0;t<e.length;t++)e[t].addEventListener("blur",r,!1),e[t].addEventListener("focus",n,!1)}}});