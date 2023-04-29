import{s as Le}from"./js/client.e7a26247.js";import{a as Ae}from"./js/_commonjsHelpers.f84db168.js";var ie={exports:{}},F={},ce={exports:{}},u={};/**
 * @license React
 * react.production.min.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */var R=Symbol.for("react.element"),Be=Symbol.for("react.portal"),Me=Symbol.for("react.fragment"),Fe=Symbol.for("react.strict_mode"),je=Symbol.for("react.profiler"),Ve=Symbol.for("react.provider"),Ie=Symbol.for("react.context"),Ze=Symbol.for("react.forward_ref"),Ue=Symbol.for("react.suspense"),Ge=Symbol.for("react.memo"),qe=Symbol.for("react.lazy"),ee=Symbol.iterator;function Ye(e){return e===null||typeof e!="object"?null:(e=ee&&e[ee]||e["@@iterator"],typeof e=="function"?e:null)}var de={isMounted:function(){return!1},enqueueForceUpdate:function(){},enqueueReplaceState:function(){},enqueueSetState:function(){}},ue=Object.assign,he={};function E(e,t,o){this.props=e,this.context=t,this.refs=he,this.updater=o||de}E.prototype.isReactComponent={};E.prototype.setState=function(e,t){if(typeof e!="object"&&typeof e!="function"&&e!=null)throw Error("setState(...): takes an object of state variables to update or a function which returns an object of state variables.");this.updater.enqueueSetState(this,e,t,"setState")};E.prototype.forceUpdate=function(e){this.updater.enqueueForceUpdate(this,e,"forceUpdate")};function fe(){}fe.prototype=E.prototype;function Y(e,t,o){this.props=e,this.context=t,this.refs=he,this.updater=o||de}var J=Y.prototype=new fe;J.constructor=Y;ue(J,E.prototype);J.isPureReactComponent=!0;var ne=Array.isArray,we=Object.prototype.hasOwnProperty,K={current:null},ye={key:!0,ref:!0,__self:!0,__source:!0};function me(e,t,o){var r,a={},l=null,i=null;if(t!=null)for(r in t.ref!==void 0&&(i=t.ref),t.key!==void 0&&(l=""+t.key),t)we.call(t,r)&&!ye.hasOwnProperty(r)&&(a[r]=t[r]);var s=arguments.length-2;if(s===1)a.children=o;else if(1<s){for(var c=Array(s),w=0;w<s;w++)c[w]=arguments[w+2];a.children=c}if(e&&e.defaultProps)for(r in s=e.defaultProps,s)a[r]===void 0&&(a[r]=s[r]);return{$$typeof:R,type:e,key:l,ref:i,props:a,_owner:K.current}}function Je(e,t){return{$$typeof:R,type:e.type,key:t,ref:e.ref,props:e.props,_owner:e._owner}}function Q(e){return typeof e=="object"&&e!==null&&e.$$typeof===R}function Ke(e){var t={"=":"=0",":":"=2"};return"$"+e.replace(/[=:]/g,function(o){return t[o]})}var ae=/\/+/g;function V(e,t){return typeof e=="object"&&e!==null&&e.key!=null?Ke(""+e.key):t.toString(36)}function B(e,t,o,r,a){var l=typeof e;(l==="undefined"||l==="boolean")&&(e=null);var i=!1;if(e===null)i=!0;else switch(l){case"string":case"number":i=!0;break;case"object":switch(e.$$typeof){case R:case Be:i=!0}}if(i)return i=e,a=a(i),e=r===""?"."+V(i,0):r,ne(a)?(o="",e!=null&&(o=e.replace(ae,"$&/")+"/"),B(a,t,o,"",function(w){return w})):a!=null&&(Q(a)&&(a=Je(a,o+(!a.key||i&&i.key===a.key?"":(""+a.key).replace(ae,"$&/")+"/")+e)),t.push(a)),1;if(i=0,r=r===""?".":r+":",ne(e))for(var s=0;s<e.length;s++){l=e[s];var c=r+V(l,s);i+=B(l,t,o,c,a)}else if(c=Ye(e),typeof c=="function")for(e=c.call(e),s=0;!(l=e.next()).done;)l=l.value,c=r+V(l,s++),i+=B(l,t,o,c,a);else if(l==="object")throw t=String(e),Error("Objects are not valid as a React child (found: "+(t==="[object Object]"?"object with keys {"+Object.keys(e).join(", ")+"}":t)+"). If you meant to render a collection of children, use an array instead.");return i}function T(e,t,o){if(e==null)return e;var r=[],a=0;return B(e,r,"","",function(l){return t.call(o,l,a++)}),r}function Qe(e){if(e._status===-1){var t=e._result;t=t(),t.then(function(o){(e._status===0||e._status===-1)&&(e._status=1,e._result=o)},function(o){(e._status===0||e._status===-1)&&(e._status=2,e._result=o)}),e._status===-1&&(e._status=0,e._result=t)}if(e._status===1)return e._result.default;throw e._result}var v={current:null},M={transition:null},Xe={ReactCurrentDispatcher:v,ReactCurrentBatchConfig:M,ReactCurrentOwner:K};u.Children={map:T,forEach:function(e,t,o){T(e,function(){t.apply(this,arguments)},o)},count:function(e){var t=0;return T(e,function(){t++}),t},toArray:function(e){return T(e,function(t){return t})||[]},only:function(e){if(!Q(e))throw Error("React.Children.only expected to receive a single React element child.");return e}};u.Component=E;u.Fragment=Me;u.Profiler=je;u.PureComponent=Y;u.StrictMode=Fe;u.Suspense=Ue;u.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED=Xe;u.cloneElement=function(e,t,o){if(e==null)throw Error("React.cloneElement(...): The argument must be a React element, but you passed "+e+".");var r=ue({},e.props),a=e.key,l=e.ref,i=e._owner;if(t!=null){if(t.ref!==void 0&&(l=t.ref,i=K.current),t.key!==void 0&&(a=""+t.key),e.type&&e.type.defaultProps)var s=e.type.defaultProps;for(c in t)we.call(t,c)&&!ye.hasOwnProperty(c)&&(r[c]=t[c]===void 0&&s!==void 0?s[c]:t[c])}var c=arguments.length-2;if(c===1)r.children=o;else if(1<c){s=Array(c);for(var w=0;w<c;w++)s[w]=arguments[w+2];r.children=s}return{$$typeof:R,type:e.type,key:a,ref:l,props:r,_owner:i}};u.createContext=function(e){return e={$$typeof:Ie,_currentValue:e,_currentValue2:e,_threadCount:0,Provider:null,Consumer:null,_defaultValue:null,_globalName:null},e.Provider={$$typeof:Ve,_context:e},e.Consumer=e};u.createElement=me;u.createFactory=function(e){var t=me.bind(null,e);return t.type=e,t};u.createRef=function(){return{current:null}};u.forwardRef=function(e){return{$$typeof:Ze,render:e}};u.isValidElement=Q;u.lazy=function(e){return{$$typeof:qe,_payload:{_status:-1,_result:e},_init:Qe}};u.memo=function(e,t){return{$$typeof:Ge,type:e,compare:t===void 0?null:t}};u.startTransition=function(e){var t=M.transition;M.transition={};try{e()}finally{M.transition=t}};u.unstable_act=function(){throw Error("act(...) is not supported in production builds of React.")};u.useCallback=function(e,t){return v.current.useCallback(e,t)};u.useContext=function(e){return v.current.useContext(e)};u.useDebugValue=function(){};u.useDeferredValue=function(e){return v.current.useDeferredValue(e)};u.useEffect=function(e,t){return v.current.useEffect(e,t)};u.useId=function(){return v.current.useId()};u.useImperativeHandle=function(e,t,o){return v.current.useImperativeHandle(e,t,o)};u.useInsertionEffect=function(e,t){return v.current.useInsertionEffect(e,t)};u.useLayoutEffect=function(e,t){return v.current.useLayoutEffect(e,t)};u.useMemo=function(e,t){return v.current.useMemo(e,t)};u.useReducer=function(e,t,o){return v.current.useReducer(e,t,o)};u.useRef=function(e){return v.current.useRef(e)};u.useState=function(e){return v.current.useState(e)};u.useSyncExternalStore=function(e,t,o){return v.current.useSyncExternalStore(e,t,o)};u.useTransition=function(){return v.current.useTransition()};u.version="18.2.0";ce.exports=u;var en=ce.exports;/**
 * @license React
 * react-jsx-runtime.production.min.js
 *
 * Copyright (c) Facebook, Inc. and its affiliates.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */var nn=en,an=Symbol.for("react.element"),tn=Symbol.for("react.fragment"),on=Object.prototype.hasOwnProperty,rn=nn.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,ln={key:!0,ref:!0,__self:!0,__source:!0};function pe(e,t,o){var r,a={},l=null,i=null;o!==void 0&&(l=""+o),t.key!==void 0&&(l=""+t.key),t.ref!==void 0&&(i=t.ref);for(r in t)on.call(t,r)&&!ln.hasOwnProperty(r)&&(a[r]=t[r]);if(e&&e.defaultProps)for(r in t=e.defaultProps,t)a[r]===void 0&&(a[r]=t[r]);return{$$typeof:an,type:e,key:l,ref:i,props:a,_owner:rn.current}}F.Fragment=tn;F.jsx=pe;F.jsxs=pe;ie.exports=F;var X=ie.exports;const q=X.Fragment,n=X.jsx,d=X.jsxs,g={};g.headline=d("svg",{width:24,height:24,viewBox:"0 0 24 24",fill:"none",xmlns:"http://www.w3.org/2000/svg",children:[n("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M15.817 2H22v.038l-6.183 4.226V2.001zm-7.62 9.473V2H2.016v4.683-.267l6.126 5.094.057-.038zm-6.182 5.061l6.183 4.213v1.252H2.015v-5.465zm13.802-.857L22 11.559v10.437h-6.183v-6.32z",fill:"#F18200"}),n("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M8.198 11.435l-.057.039L2.016 6.38v.265-4.644h6.182v9.434zm0 9.226L2.015 16.45v5.55h6.183v-1.337zm7.62-5.07L22 11.474v10.522h-6.183v-6.405zM22 2.001l-6.183 4.226V2H22z",fill:"#F18200"}),n("path",{d:"M8.141 13.537L22 4.064v5.432l-13.8 9.19L2 14.46l.016-6.018 6.125 5.094z",fill:"#F18200"})]});g.headlineBlack=n("svg",{width:"15",height:"14",viewBox:"0 0 15 14",fill:"none",xmlns:"http://www.w3.org/2000/svg",children:n("path",{d:"M13.5 2V12H14.5C14.6458 12 14.7604 12.0521 14.8438 12.1562C14.9479 12.2396 15 12.3542 15 12.5V13.5C15 13.6458 14.9479 13.7604 14.8438 13.8438C14.7604 13.9479 14.6458 14 14.5 14H9.5C9.35417 14 9.22917 13.9479 9.125 13.8438C9.04167 13.7604 9 13.6458 9 13.5V12.5C9 12.3542 9.04167 12.2396 9.125 12.1562C9.22917 12.0521 9.35417 12 9.5 12H10.5V8H4.5V12H5.5C5.64583 12 5.76042 12.0521 5.84375 12.1562C5.94792 12.2396 6 12.3542 6 12.5V13.5C6 13.6458 5.94792 13.7604 5.84375 13.8438C5.76042 13.9479 5.64583 14 5.5 14H0.5C0.354167 14 0.229167 13.9479 0.125 13.8438C0.0416667 13.7604 0 13.6458 0 13.5V12.5C0 12.3542 0.0416667 12.2396 0.125 12.1562C0.229167 12.0521 0.354167 12 0.5 12H1.5V2H0.5C0.354167 2 0.229167 1.95833 0.125 1.875C0.0416667 1.77083 0 1.64583 0 1.5V0.5C0 0.354167 0.0416667 0.239583 0.125 0.15625C0.229167 0.0520833 0.354167 0 0.5 0H5.5C5.64583 0 5.76042 0.0520833 5.84375 0.15625C5.94792 0.239583 6 0.354167 6 0.5V1.5C6 1.64583 5.94792 1.77083 5.84375 1.875C5.76042 1.95833 5.64583 2 5.5 2H4.5V6H10.5V2H9.5C9.35417 2 9.22917 1.95833 9.125 1.875C9.04167 1.77083 9 1.64583 9 1.5V0.5C9 0.354167 9.04167 0.239583 9.125 0.15625C9.22917 0.0520833 9.35417 0 9.5 0H14.5C14.6458 0 14.7604 0.0520833 14.8438 0.15625C14.9479 0.239583 15 0.354167 15 0.5V1.5C15 1.64583 14.9479 1.77083 14.8438 1.875C14.7604 1.95833 14.6458 2 14.5 2H13.5Z",fill:"#000"})});g.warning=n("svg",{width:"20",height:"20",viewBox:"0 0 20 20",fill:"none",xmlns:"http://www.w3.org/2000/svg",children:n("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M9.99 0C4.47 0 0 4.48 0 10C0 15.52 4.47 20 9.99 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 9.99 0ZM11 5.00002H9V11H11V5.00002ZM11 13H9V15H11V13ZM2.00002 10C2.00002 14.42 5.58002 18 10 18C14.42 18 18 14.42 18 10C18 5.58002 14.42 2.00002 10 2.00002C5.58002 2.00002 2.00002 5.58002 2.00002 10Z",fill:"#005AE0"})});g.smile=n("svg",{width:"20",height:"20",viewBox:"0 0 20 20",fill:"none",xmlns:"http://www.w3.org/2000/svg",children:n("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M9.99 0C4.47 0 0 4.48 0 10C0 15.52 4.47 20 9.99 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 9.99 0ZM6.5 6C5.67157 6 5 6.67157 5 7.5C5 8.32843 5.67157 9 6.5 9C7.32843 9 8 8.32843 8 7.5C8 6.67157 7.32843 6 6.5 6ZM13.5 6C12.6716 6 12 6.67157 12 7.5C12 8.32843 12.6716 9 13.5 9C14.3284 9 15 8.32843 15 7.5C15 6.67157 14.3284 6 13.5 6ZM6.55 12C7.25 13.19 8.52 14 10 14C11.48 14 12.75 13.19 13.45 12H15.12C14.32 14.05 12.33 15.5 10 15.5C7.67 15.5 5.68 14.05 4.88 12H6.55ZM2 10C2 14.42 5.58 18 10 18C14.42 18 18 14.42 18 10C18 5.58 14.42 2 10 2C5.58 2 2 5.58 2 10Z",fill:"#00AA63"})});g.neutral=n("svg",{width:"20",height:"20",viewBox:"0 0 20 20",fill:"none",xmlns:"http://www.w3.org/2000/svg",children:n("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M9.99 0C4.47 0 0 4.48 0 10C0 15.52 4.47 20 9.99 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 9.99 0ZM5 7.5C5 6.67157 5.67157 6 6.5 6C7.32843 6 8 6.67157 8 7.5C8 8.32843 7.32843 9 6.5 9C5.67157 9 5 8.32843 5 7.5ZM13.5 6C12.6716 6 12 6.67157 12 7.5C12 8.32843 12.6716 9 13.5 9C14.3284 9 15 8.32843 15 7.5C15 6.67157 14.3284 6 13.5 6ZM7 13.5V12H13V13.5H7ZM2 10C2 14.42 5.58 18 10 18C14.42 18 18 14.42 18 10C18 5.58 14.42 2 10 2C5.58 2 2 5.58 2 10Z",fill:"#005AE0"})});g.negative=n("svg",{width:"20",height:"20",viewBox:"0 0 20 20",fill:"none",xmlns:"http://www.w3.org/2000/svg",children:n("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M9.99 0C4.47 0 0 4.48 0 10C0 15.52 4.47 20 9.99 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 9.99 0ZM6.5 6C5.67157 6 5 6.67157 5 7.5C5 8.32843 5.67157 9 6.5 9C7.32843 9 8 8.32843 8 7.5C8 6.67157 7.32843 6 6.5 6ZM13.5 6C12.6716 6 12 6.67157 12 7.5C12 8.32843 12.6716 9 13.5 9C14.3284 9 15 8.32843 15 7.5C15 6.67157 14.3284 6 13.5 6ZM4.88 15.5C5.68 13.45 7.67 12 10 12C12.33 12 14.32 13.45 15.12 15.5H13.45C12.75 14.31 11.48 13.5 10 13.5C8.52 13.5 7.24 14.31 6.55 15.5H4.88ZM2 10C2 14.42 5.58 18 10 18C14.42 18 18 14.42 18 10C18 5.58 14.42 2 10 2C5.58 2 2 5.58 2 10Z",fill:"#DF2A4A"})});g.check=n("svg",{width:"20",height:"20",viewBox:"0 0 20 20",fill:"none",xmlns:"http://www.w3.org/2000/svg",children:n("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM10 18C5.59 18 2 14.41 2 10C2 5.59 5.59 2 10 2C14.41 2 18 5.59 18 10C18 14.41 14.41 18 10 18ZM8 12.17L14.59 5.58L16 7L8 15L4 11L5.41 9.59L8 12.17Z",fill:"#00AA63"})});const ge=e=>Le.agent().set("X-WP-Nonce",e).use(t=>{t.on("response",o=>{(o.status===401||o.status===403)&&console.error(o)})}),sn=e=>e.replace(/^\//,""),ve=e=>e.replace(/\/$/,""),I=e=>ve(e)+"/",be=e=>(e=window.aioseo.data.hasUrlTrailingSlash?I(e):ve(e),I(window.aioseo.urls.restUrl)+I("aioseo/v1")+sn(e));var Ce={exports:{}};/*!
	Copyright (c) 2018 Jed Watson.
	Licensed under the MIT License (MIT), see
	http://jedwatson.github.io/classnames
*/(function(e){(function(){var t={}.hasOwnProperty;function o(){for(var r=[],a=0;a<arguments.length;a++){var l=arguments[a];if(l){var i=typeof l;if(i==="string"||i==="number")r.push(l);else if(Array.isArray(l)){if(l.length){var s=o.apply(null,l);s&&r.push(s)}}else if(i==="object"){if(l.toString!==Object.prototype.toString&&!l.toString.toString().includes("[native code]")){r.push(l.toString());continue}for(var c in l)t.call(l,c)&&l[c]&&r.push(c)}}}return r.join(" ")}e.exports?(o.default=o,e.exports=o):window.classNames=o})()})(Ce);var cn=Ce.exports;const p=Ae(cn),{Fragment:dn}=window.wp.element,_e=e=>{const t=e.barScore,o=e.color;return n(dn,{children:n("div",{className:"aioseo-donut-container",style:{flexDirection:"column"},children:d("svg",{className:"aioseo-donut-score-svg",viewBox:"0 0 33.83098862 33.83098862",xmlns:"http://www.w3.org/2000/svg",children:[n("circle",{className:"aioseo-seo-headline-analyzer-score__background",stroke:"#e8e8eb",strokeWidth:"2",fill:"none",cx:"16.91549431",cy:"16.91549431",r:"15.91549431"}),n("circle",{className:"aioseo-seo-headline-analyzer-score__circle",stroke:o,strokeWidth:"2",strokeDasharray:`${t}, 100`,strokeLinecap:"round",fill:"none",cx:"16.91549431",cy:"16.91549431",r:"15.91549431"})]})})})},{__:j,sprintf:O}=window.wp.i18n,un=O(j("Headlines that are lists and how-to get more engagement on average than other types of headlines. %1$s%2$sLearn More%3$s →","all-in-one-seo-pack"),"<br /><br />",'<a href="https://optinmonster.com/why-these-21-headlines-went-viral-and-how-you-can-copy-their-success/" target="_blank" className="aioseo-headline-analyzer-link"><span>',"</span></a>"),ze=O(j("A very good score is between %1$d and %2$d.","all-in-one-seo-pack"),70,90),Se=O(j("For best results, you should strive for %1$d and above.","all-in-one-seo-pack"),70),hn=O(j("This headline analyzer is part of AIOSEO to help you increase your traffic. %1$sAnalyze your site further here%2$s →%3$s","all-in-one-seo-pack"),O('<a href="%1$s" className="aioseo-headline-analyzer-link" target="_blank"><span>',window.aioseo.urls.aio.seoAnalysis),"</span>","</a>"),{__:H}=window.wp.i18n,{Fragment:fn}=window.wp.element,{PanelBody:wn,PanelRow:yn}=window.wp.components,D="all-in-one-seo-pack",mn=e=>{const t=e.analyzer.currentHeadlineData.sentence,o=H("Score",D),r=e.analyzer.currentHeadlineData.score,a=40>r?"red":70>=r?"orange":"green",l=a==="red"?"#df2a4a":a==="orange"?"#F2994A":"#00aa63";let i;switch(!0){case 25>r:i=n("span",{children:H("Not Looking Great",D)});break;case 50>r:i=n("span",{children:H("Could Be Better",D)});break;case 60>r:i=n("span",{children:H("Getting There",D)});break;case 75>r:i=n("span",{children:H("Looks Good! 👍👍",D)});break;case 75<r:i=n("span",{children:H("Super! 🔥🔥🔥",D)});break;default:i=!1}return n(fn,{children:n(wn,{className:"aioseo-headline-analyzer-panel-score",title:o,children:n(yn,{children:d("div",{className:"aioseo-headline-analyzer-current-score-tab aioseo-headline-analyzer-panel-first-block",children:[d("h4",{className:"aioseo-headline-analyzer-current-title",children:["“",t,"”"]}),d("div",{className:"aioseo-headline-analyzer-pie-chart-container",children:[d("div",{className:p("aioseo-headline-analyzer-current-score",a),children:[r,n("span",{className:"aioseo-headline-analyzer-total-out-of-score",children:"/ 100"})]}),i&&n("div",{className:p("aioseo-headline-analyzer-score-status",a),children:i}),n(_e,{barScore:r,color:l})]}),d("p",{children:[ze," ",Se]})]})})})})},{__:Z}=window.wp.i18n,{Fragment:pn}=window.wp.element,{PanelBody:gn,PanelRow:vn,TextControl:bn,Button:Cn}=window.wp.components,U="all-in-one-seo-pack",_n=e=>{const t=Z("Try New Headline",U),o=Z("Enter a different headline than your post title to see how it compares.",U),r=Z("Analyze Headline",U),a=typeof e.analyzer.newHeadline<"u"?e.analyzer.newHeadline:"",l=typeof e.analyzer.previousHeadlinesData<"u"?e.analyzer.previousHeadlinesData:[],i=!a,s=c=>{const w=c.trim();w&&ge(window.aioseo.nonce).post(be("analyze_headline")).send({headline:w,shouldStoreHeadline:!1}).then(f=>{const h=JSON.parse(f.body[Object.keys(f.body)[0]]);e.setAnalyzer({newHeadlineData:h,headlineData:h,previousHeadlinesData:[e.analyzer.headlineData,...l],isNewData:!0})}).catch(f=>{e.setAnalyzer({isNewData:!1}),console.log(f)})};return n(pn,{children:n(gn,{title:t,className:p("aioseo-headline-analyzer-panel-tab-new-score-form"),children:n(vn,{children:n("div",{className:"aioseo-headline-analyzer-new-tab",children:n("div",{className:"aioseo-headline-analyzer-new-score-form-block",children:d("form",{onSubmit:c=>{c.preventDefault(),s(a)},children:[n(bn,{label:o,value:a,onChange:c=>{c!==" "&&e.setAnalyzer({newHeadline:c})},className:"aioseo-headline-analyzer-input-field"}),n(Cn,{className:p("aioseo-headline-analyzer-button"),onClick:()=>{s(a)},disabled:i,children:r})]})})})})})})},{__:N}=window.wp.i18n,{Fragment:zn}=window.wp.element,{PanelBody:Sn,PanelRow:kn}=window.wp.components,P="all-in-one-seo-pack",Nn=e=>{const t=e.analyzer.currentHeadlineData.sentence,o=N("New Score",P),r=N("Current Score",P),a=typeof e.analyzer.newHeadlineData<"u"?e.analyzer.newHeadlineData.sentence:"",l=typeof e.analyzer.newHeadlineData<"u"?e.analyzer.newHeadlineData.score:"",i=typeof e.analyzer.currentHeadlineData.score<"u"?e.analyzer.currentHeadlineData.score:"",s=40>l?"red":60>=l?"orange":"green",c=s==="red"?"#df2a4a":s==="orange"?"#F2994A":"#00aa63",w=40>i?"red-bg":60>=i?"orange-bg":"green-bg",f=Math.abs(l-i);let h;switch(!0){case 25>l:h=n("span",{children:N("Not Looking Great",P)});break;case 50>l:h=n("span",{children:N("Could Be Better",P)});break;case 60>l:h=n("span",{children:N("Getting There",P)});break;case 75>l:h=n("span",{children:N("Looks Good! 👍👍",P)});break;case 75<l:h=n("span",{children:N("Super! 🔥🔥🔥",P)});break;default:h=!1}return n(zn,{children:n(Sn,{title:o,className:"aioseo-headline-analyzer-panel-tab-new-score",children:n(kn,{children:n("div",{className:"aioseo-headline-analyzer-panel-first-block",children:d("div",{className:"aioseo-headline-analyzer-new-score-panel",children:[d("p",{children:[ze," ",Se]}),d("h4",{children:["“",a,"”"]}),d("div",{className:"aioseo-headline-analyzer-pie-chart-container",children:[n("span",{className:p("aioseo-headline-analyzer-new-score",s),children:l}),n(_e,{barScore:l,color:c}),d("span",{className:p("aioseo-headline-analyzer-score-difference",s),children:[l>i?"+ ":l===i?"":"- ",f]}),h&&n("div",{className:p("aioseo-headline-analyzer-score-status",s),children:h})]}),d("div",{className:"current-score",children:[n("span",{className:p("aioseo-headline-analyzer-score",w),children:i}),d("div",{className:"aioseo-headline-analyzer-current-score-content",children:[n("h5",{children:r}),n("p",{children:t})]})]})]})})})})})},{__:te}=window.wp.i18n,{Fragment:Pn,useState:xn,useEffect:$n}=window.wp.element,Hn=e=>{const t=te("Current Score","all-in-one-seo-pack"),o=te("Try New Headline","all-in-one-seo-pack"),[r,a]=xn("current-score"),l=()=>a("current-score"),i=()=>a("new-headline"),s=typeof e.analyzer.isNewData<"u"?e.analyzer.isNewData:!1;return $n(()=>{e.setAnalyzer({activeTab:r})},[r]),d(Pn,{children:[d("div",{className:"aioseo-inline-buttons",children:[n("button",{onClick:l,className:p("aioseo-switcher-button",{active:r==="current-score"}),children:t}),n("button",{onClick:i,className:p("aioseo-switcher-button",{active:r==="new-headline"}),children:o})]}),r==="new-headline"?n(q,{children:n(_n,{analyzer:e.analyzer,setAnalyzer:e.setAnalyzer})}):n(mn,{analyzer:e.analyzer}),s?n(Nn,{analyzer:e.analyzer}):""]})},{__:Dn}=window.wp.i18n,{Fragment:En}=window.wp.element,{PanelBody:On,PanelRow:Rn}=window.wp.components,Tn=e=>{const t=Dn("Previous Scores","all-in-one-seo-pack"),o=e.analyzer.previousHeadlinesData!=="undefined"?e.analyzer.previousHeadlinesData:[],r=document.querySelector(".edit-post-sidebar"),l=(e.analyzer.activeTab!=="undefined"?e.analyzer.activeTab:"current-score")==="current-score"?390:300,i=s=>{e.setAnalyzer({newHeadlineData:o[s],headlineData:o[s],newHeadline:o[s].sentence,isNewData:!0}),r&&(r.scrollTop=l)};return n(En,{children:n(On,{title:t,className:"aioseo-headline-analyzer-panel-previous-scores",children:n(Rn,{children:n("div",{className:"aioseo-headline-analyzer-panel-first-block",children:n("ul",{className:"aioseo-headline-analyzer-previous-scores",children:o.map((s,c)=>{if(10>c&&(typeof s.sentence<"u"||typeof s.score<"u")){const w=40>s.score?"red-bg":60>=s.score?"orange-bg":"green-bg";return d("li",{onClick:()=>i(c),children:[n("span",{className:p("aioseo-headline-analyzer-score",w),children:s.score}),n("span",{className:"aioseo-headline-analyzer-score-text",children:s.sentence})]},c)}return null})})})})})})},{__:Wn}=window.wp.i18n,W=e=>{const t=Wn("Goal: ","all-in-one-seo-pack"),o={width:e.value+"%"};return d("div",{className:"aioseo-headline-analyzer-words-block",children:[n("h5",{children:e.title}),d("div",{className:"aioseo-headline-analyzer-words-block-data",children:[d("span",{className:p("aioseo-headline-analyzer-words-block-percentage",e.classOnScore),children:[e.value,"%"]}),d("span",{className:p("aioseo-headline-analyzer-words-block-percentage-right-column",e.classOnScore),children:[d("span",{className:"aioseo-headline-analyzer-words-block-goal",children:[t," ",e.goalValue]}),d("span",{className:"aioseo-headline-analyzer-words-block-progressbar",children:[n("span",{className:"aioseo-headline-analyzer-progressbar-bg"}),n("span",{className:p("aioseo-headline-analyzer-progressbar-part",e.classOnScoreBg),style:o})]})]})]}),n("ul",{className:"aioseo-headline-analyzer-words-tag-list",children:0<e.words.length?e.words.map((r,a)=>n("li",{children:r},a)):""}),n("p",{className:"aioseo-headline-analyzer-words-guideline",children:e.guideLine})]})},{__:y}=window.wp.i18n,{Fragment:Ln}=window.wp.element,{PanelBody:An,PanelRow:Bn}=window.wp.components,m="all-in-one-seo-pack",Mn=e=>{const t=y("Word Balance",m),o=y("Compare the percentages of your results to the goal for each category and adjust as necessary.",m),r=e.data.score,a=40>e.data.score?"red":60>=e.data.score?"orange":"green",l=e.data.result.commonWordsPercentage===0?"red":.2>e.data.result.commonWordsPercentage?"orange":"green",i=e.data.result.commonWordsPercentage===0?"red-bg":.2>e.data.result.commonWordsPercentage?"orange-bg":"green-bg",s=.2>e.data.result.commonWordsPercentage?y("Your headline would be more likely to get clicks if it had more common words.",m):y("Headlines with 20-30% common words are more likely to get clicks.",m),c=e.data.result.uncommonWordsPercentage===0?"red":.1>e.data.result.uncommonWordsPercentage?"orange":"green",w=e.data.result.uncommonWordsPercentage===0?"red-bg":.1>e.data.result.uncommonWordsPercentage?"orange-bg":"green-bg",f=.1>e.data.result.uncommonWordsPercentage?y("Your headline would be more likely to get clicks if it had more uncommon words.",m):y("Headlines with uncommon words are more likely to get clicks.",m),h=e.data.result.emotionalWordsPercentage===0?"red":.1>e.data.result.emotionalWordsPercentage?"orange":"green",b=e.data.result.emotionalWordsPercentage===0?"red-bg":.1>e.data.result.emotionalWordsPercentage?"orange-bg":"green-bg",ke=y("Emotionally triggered headlines are likely to drive more clicks.",m),Ne=e.data.result.powerWords.length===0?"orange":"green",Pe=e.data.result.powerWords.length===0?"orange":"green-bg",xe=y("Headlines with power words are more likely to get clicks.",m),$e=y("Common Words",m),He=y("20-30%",m),De=y("Uncommon Words",m),Ee=y("10-20%",m),Oe=y("Emotional Words",m),Re=y("10-15%",m),Te=y("Power Words",m),We=y("At least one",m);let k;switch(!0){case 25>r:k=n("span",{children:y("Not Looking Great",m)});break;case 50>r:k=n("span",{children:y("Could Be Better",m)});break;case 60>r:k=n("span",{children:y("Getting There",m)});break;case 75>r:k=n("span",{children:y("Looks Good! 👍👍",m)});break;case 75<r:k=n("span",{children:y("Super! 🔥🔥🔥",m)});break;default:k=!1}return n(Ln,{children:n(An,{title:t,className:p("aioseo-headline-analyzer-panel-word-balance","aioseo-headline-analyzer-panel-has-icon",a),icon:a==="green"?g.check:g.warning,children:d(Bn,{children:[d("div",{className:"aioseo-headline-analyzer-words-block",children:[n("h4",{children:k}),n("p",{children:o})]}),n(W,{title:$e,value:Math.round(e.data.result.commonWordsPercentage*100),goalValue:He,words:e.data.result.commonWords,guideLine:s,classOnScore:l,classOnScoreBg:i}),n(W,{title:De,value:Math.round(e.data.result.uncommonWordsPercentage*100),goalValue:Ee,words:e.data.result.uncommonWords,guideLine:f,classOnScore:c,classOnScoreBg:w}),n(W,{title:Oe,value:Math.round(e.data.result.emotionalWordsPercentage*100),goalValue:Re,words:e.data.result.emotionWords,guideLine:ke,classOnScore:h,classOnScoreBg:b}),n(W,{title:Te,value:Math.round(e.data.result.powerWordsPercentage*100),goalValue:We,words:e.data.result.powerWords,guideLine:xe,classOnScore:Ne,classOnScoreBg:Pe})]})})})},{__:C}=window.wp.i18n,{Fragment:Fn}=window.wp.element,{PanelBody:jn,PanelRow:Vn}=window.wp.components,_="all-in-one-seo-pack",In=e=>{const t=C("Your headline has a neutral sentiment.",_),o=C("Headlines that are strongly positive or negative tend to get more engagement than neutral ones.",_),r=C("Your headline has a positive sentiment.",_),a=C("Positive headlines tend to get better engagement than neutral or negative ones.",_),l=C("Your headline has a negative sentiment.",_),i=C("Negative headlines are attention-grabbing and tend to perform better than neutral ones.",_),s=C("Sentiment",_),c=e.data.result.sentiment==="neu"?C("Neutral",_):e.data.result.sentiment==="pos"?C("Positive",_):C("Negative",_),w=e.data.result.sentiment==="neu"?g.neutral:e.data.result.sentiment==="pos"?g.smile:g.negative,f=e.data.result.sentiment==="neu"?"orange":e.data.result.sentiment==="pos"?"green":"red";return n(Fn,{children:n(jn,{title:s,className:p("aioseo-headline-analyzer-panel-sentiment","aioseo-headline-analyzer-panel-has-icon",f),icon:w,children:n(Vn,{children:d("div",{className:"aioseo-headline-analyzer-panel-first-block",children:[n("h4",{children:c}),e.data.result.sentiment==="neu"?d("p",{children:[n("strong",{children:t}),n("br",{}),o]}):"",e.data.result.sentiment==="pos"?d("p",{children:[n("strong",{children:r}),n("br",{}),a]}):"",e.data.result.sentiment==="neg"?d("p",{children:[n("strong",{children:l}),n("br",{})," ",i]}):""]})})})})},{__:Zn}=window.wp.i18n,{Fragment:Un}=window.wp.element,{PanelBody:Gn,PanelRow:qn}=window.wp.components,Yn=e=>{const t=e.data.result.headlineTypes.join(", "),o=Zn("Headline Type","all-in-one-seo-pack");return n(Un,{children:n(Gn,{title:d("span",{className:"aioseo-headline-analyzer-panel-types-title",children:[o,n("span",{children:t})]}),className:"aioseo-headline-analyzer-panel-types",children:n(qn,{children:d("div",{className:"aioseo-headline-analyzer-panel-first-block",children:[n("h4",{children:t}),n("p",{dangerouslySetInnerHTML:{__html:un}})]})})})})},{__:x}=window.wp.i18n,{Fragment:Jn}=window.wp.element,{PanelBody:Kn,PanelRow:Qn}=window.wp.components,$="all-in-one-seo-pack",Xn=e=>{const t=x("Character Count",$),o=e.data.result.length,r=o.toString();let a="",l="",i="",s="";if(r.length===1&&(s=`<span class="character-zero">0</span><span class="character-zero">0</span><span>${o}</span>`),r.length===2){s='<span class="character-zero">0</span>';for(const c of r)s+=`<span>${c}</span>`}if(o.toString().length===3)for(const c of r)s+=`<span>${c}</span>`;return 19>=o?a="red":20<=o&&34>=o?a="orange":35<=o&&66>=o?a="green":67<=o&&79>=o?a="orange":80<=o&&(a="red"),34>=o?(l=x("Too Short 🙃",$),i=x("You have space to add more keywords and power words to boost your rankings and click-through rate.",$)):35<=o&&66>=o?(l=x("Good 🙂",$),i=x("Headlines that are about 55 characters long will display fully in search results and tend to get more clicks.",$)):67<=o&&(l=x("Too Long 😑",$),i=x("At this length, it will get cut off in search results. Try reducing it to about 55 characters.",$)),n(Jn,{children:n(Kn,{title:t,className:p("aioseo-headline-analyzer-panel-character-count","aioseo-headline-analyzer-panel-has-icon",a),icon:a==="green"?g.check:g.warning,children:n(Qn,{children:d("div",{className:"aioseo-headline-analyzer-panel-first-block",children:[d("div",{className:"aioseo-headline-analyzer-character-count-container",children:[n("span",{className:"aioseo-headline-analyzer-status-on-character-length",children:l}),n("span",{className:p("aioseo-headline-analyzer-character-length",a),dangerouslySetInnerHTML:{__html:s}})]}),n("p",{children:i})]})})})})},{__:z}=window.wp.i18n,{Fragment:ea}=window.wp.element,{PanelBody:na,PanelRow:aa}=window.wp.components,S="all-in-one-seo-pack",ta=e=>{const t=z("Word Count",S),o=e.data.result.wordCount,r=o.toString();let a="",l="",i="",s="";if(r.length===1&&(s=`<span class="character-zero">0</span><span class="character-zero">0</span><span>${o}</span>`),r.length===2){s='<span class="character-zero">0</span>';for(const c of r)s+=`<span>${c}</span>`}if(r.length===3)for(const c of r)s+=`<span>${c}</span>`;return 4>=o?(a="red",l=z("Not Enough Words 🙃",S),i=z("Your headline doesn’t use enough words. You have more space to add keywords and power words to improve your SEO and get more engagement.",S)):5<=o&&9>=o?(a="green",l=z("Good 🙂",S),i=z("Your headline has the right amount of words. Headlines are more likely to be clicked on in search results if they have about 6 words.",S)):10<=o&&11>=o?(a="orange",l=z("Reduce Word Count 🙂",S)):(a="red",l=z("Too Many Words 😑",S),i=z("Your headline has too many words. Long headlines will get cut off in search results and won’t get as many clicks.",S)),n(ea,{children:n(na,{title:t,className:p("aioseo-headline-analyzer-panel-word-count","aioseo-headline-analyzer-panel-has-icon",a),icon:a==="green"?g.check:g.warning,children:n(aa,{children:d("div",{className:"aioseo-headline-analyzer-panel-first-block",children:[d("div",{className:"aioseo-headline-analyzer-word-counter",children:[n("span",{className:"aioseo-headline-analyzer-status-on-word-length",children:l}),n("span",{className:p("aioseo-headline-analyzer-word-length",a),dangerouslySetInnerHTML:{__html:s}})]}),n("p",{children:i})]})})})})},{__:L}=window.wp.i18n,{Fragment:oa}=window.wp.element,{PanelBody:ra,PanelRow:la}=window.wp.components,A="all-in-one-seo-pack",sa=e=>{const t=L("Beginning & Ending Words",A),o=e.data.result.originalExplodedHeadline,r=L("Most readers only look at the first and last 3 words of a headline before deciding whether to click.",A);let a="",l="";return 6<=o.length?(a=o.slice(0,3).join(" "),l=o.slice(-3).join(" ")):3<o.length&&5>=o.length?(a=o.slice(0,3).join(" "),l=o.slice(3).join(" ")):a=o.slice(0,3).join(" "),n(oa,{children:n(ra,{title:t,className:"aioseo-headline-analyzer-panel-beginning-ending-words",children:n(la,{children:d("div",{className:"aioseo-headline-analyzer-panel-first-block",children:[a?d(q,{children:[n("ul",{className:"aioseo-headline-analyzer-word-begining-title",children:n("li",{children:L("Beginning Words",A)})}),n("div",{className:"aioseo-headline-analyzer-words beginning",children:n("span",{children:a})})]}):"",l?d(q,{children:[n("ul",{className:"aioseo-headline-analyzer-word-ending-title",children:n("li",{children:L("Ending Words",A)})}),n("div",{className:"aioseo-headline-analyzer-words ending",children:n("span",{children:l})})]}):"",n("p",{className:"aioseo-headline-analyzer-words-guideline",children:r})]})})})})},{__:oe}=window.wp.i18n,{Fragment:ia}=window.wp.element,{PanelBody:ca,PanelRow:da}=window.wp.components,{select:ua}=window.wp.data,ha=e=>{const t=oe("Search Preview","all-in-one-seo-pack"),o=oe("Here is how your headline will look like in Google search results page.","all-in-one-seo-pack"),r=ua("core/editor").getPermalink();return n(ia,{children:n(ca,{title:t,className:"aioseo-headline-analyzer-panel-search-preview",children:n(da,{children:n("div",{className:"aioseo-headline-analyzer-panel-first-block",children:d("div",{className:"aioseo-headline-analyzer-search-prevew-wrap",children:[n("p",{className:"aioseo-headline-analyzer-post-url",children:n("a",{...{href:r},target:"_blank",children:r})}),n("h4",{children:e.data.sentence}),n("p",{children:o})]})})})})})};const{__:re}=window.wp.i18n,{Fragment:fa,useState:wa,useEffect:ya}=window.wp.element,{registerPlugin:ma}=window.wp.plugins,{PluginSidebar:pa,PluginSidebarMoreMenuItem:ga}=window.wp.editPost,{select:G}=window.wp.data,le="all-in-one-seo-pack";let se;const va=(e,t)=>((...o)=>{const r=()=>e(...o);clearTimeout(se),se=setTimeout(r,t)}).call(),ba=()=>{let e=G("core/editor").getEditedPostAttribute("title");const t=()=>{if(!e){l({...a,...{dataExist:!1}});return}ge(window.aioseo.nonce).post(be("analyze_headline")).send({headline:e,shouldStoreHeadline:!1}).then(f=>{const h={dataExist:!1},b=JSON.parse(f.body[Object.keys(f.body)[0]]);b.analysed&&(h.currentHeadlineData=b,h.headlineData=b,h.dataExist=!0,typeof a.headlineData<"u"&&(h.previousHeadlinesData=[a.headlineData,...i])),l({...a,...h})}).catch(f=>{l({...a,...{dataExist:!1}}),console.log("Couldn't fetch score for headline:",f)})};ya(()=>t(),[]),window.wp.data.subscribe(()=>{e!==G("core/editor").getEditedPostAttribute("title")&&(e=G("core/editor").getEditedPostAttribute("title"),va(()=>t(),2e3))});const o=re("SEO Headline Analyzer",le),r=re("Write your post title to see the analyzer data. This Headline Analyzer tool enables you to write irresistible SEO headlines that drive traffic, shares, and rank better in search results.",le),[a,l]=wa({}),i=typeof a.previousHeadlinesData<"u"?a.previousHeadlinesData:[],s=document.querySelector(`.components-button[aria-label='${o}'] svg`);if(s){const f=document.createElement("span");if(a.dataExist&&typeof a.currentHeadlineData.score<"u"){const h=a.currentHeadlineData.score,b=40>h?"red":60>=h?"orange":"green";s.parentNode.setAttribute("aioseo-button-color",b),s.nextElementSibling?s.nextElementSibling.innerHTML=`${h}/100`:(f.innerHTML=`${h}/100`,s.parentNode.insertBefore(f,s.nextSibling))}else s.parentNode.setAttribute("aioseo-button-color","red"),s.nextElementSibling?s.nextElementSibling.innerHTML="00/100":(f.innerHTML="00/100",s.parentNode.insertBefore(f,s.nextSibling))}const c=document.querySelector(".aioseo-headline-analyzer-wrapper");if(c){const f=c.parentNode.querySelectorAll(".components-panel__header");f&&f.forEach(function(h){const b=h.querySelector(".interface-complementary-area__pin-unpin-item");b&&b!==null&&(b.style.display="none")})}const w=f=>{l({...a,...f})};return d(fa,{children:[n(ga,{target:"aioseo-headline-analyzer",children:o}),d(pa,{name:"aioseo-headline-analyzer",title:o,className:"aioseo-headline-analyzer-wrapper",children:[typeof a.headlineData<"u"&&a.dataExist&&a.headlineData.analysed?n(Hn,{analyzer:a,setAnalyzer:w}):n("p",{className:"aioseo-headline-analyzer-empty-title-warning",children:r}),typeof a.headlineData<"u"&&a.dataExist&&a.headlineData.analysed&&0<i.length?n(Tn,{analyzer:a,setAnalyzer:w}):"",typeof a.headlineData<"u"&&a.dataExist&&a.headlineData.analysed?n(Mn,{data:a.headlineData}):"",typeof a.headlineData<"u"&&a.dataExist&&a.headlineData.analysed?n(In,{data:a.headlineData}):"",typeof a.headlineData<"u"&&a.dataExist&&a.headlineData.analysed?n(Yn,{data:a.headlineData}):"",typeof a.headlineData<"u"&&a.dataExist&&a.headlineData.analysed?n(Xn,{data:a.headlineData}):"",typeof a.headlineData<"u"&&a.dataExist&&a.headlineData.analysed?n(ta,{data:a.headlineData}):"",typeof a.headlineData<"u"&&a.dataExist&&a.headlineData.analysed?n(sa,{data:a.headlineData}):"",typeof a.headlineData<"u"&&a.dataExist&&a.headlineData.analysed?n(ha,{data:a.headlineData}):"",n("div",{className:"aioseo-headline-analyzer-bottom-notice",children:n("p",{dangerouslySetInnerHTML:{__html:hn}})})]})]})};ma("aioseo-headline-analyzer",{icon:g.headline,render:ba});
