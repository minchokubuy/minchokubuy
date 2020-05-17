!function(e){var t={};function n(o){if(t[o])return t[o].exports;var r=t[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(o,r,function(t){return e[t]}.bind(null,r));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=15)}([function(e,t){!function(){e.exports=this.wp.element}()},function(e,t){e.exports=function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}},function(e,t,n){var o;
/*!
  Copyright (c) 2017 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/!function(){"use strict";var n={}.hasOwnProperty;function r(){for(var e=[],t=0;t<arguments.length;t++){var o=arguments[t];if(o){var i=typeof o;if("string"===i||"number"===i)e.push(o);else if(Array.isArray(o)&&o.length){var s=r.apply(null,o);s&&e.push(s)}else if("object"===i)for(var a in o)n.call(o,a)&&o[a]&&e.push(a)}}return e.join(" ")}e.exports?(r.default=r,e.exports=r):void 0===(o=function(){return r}.apply(t,[]))||(e.exports=o)}()},function(e,t,n){var o=n(12);e.exports=function(e,t){if(null==e)return{};var n,r,i=o(e,t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(e);for(r=0;r<s.length;r++)n=s[r],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(i[n]=e[n])}return i}},function(e,t){e.exports=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}},function(e,t){function n(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}e.exports=function(e,t,o){return t&&n(e.prototype,t),o&&n(e,o),e}},function(e,t,n){var o=n(13),r=n(1);e.exports=function(e,t){return!t||"object"!==o(t)&&"function"!=typeof t?r(e):t}},function(e,t){function n(t){return e.exports=n=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)},n(t)}e.exports=n},function(e,t,n){var o=n(14);e.exports=function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&o(e,t)}},function(e,t){!function(){e.exports=this.lodash}()},function(e,t){function n(){return e.exports=n=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var o in n)Object.prototype.hasOwnProperty.call(n,o)&&(e[o]=n[o])}return e},n.apply(this,arguments)}e.exports=n},function(e,t){e.exports=function(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}},function(e,t){e.exports=function(e,t){if(null==e)return{};var n,o,r={},i=Object.keys(e);for(o=0;o<i.length;o++)n=i[o],t.indexOf(n)>=0||(r[n]=e[n]);return r}},function(e,t){function n(t){return"function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?e.exports=n=function(e){return typeof e}:e.exports=n=function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},n(t)}e.exports=n},function(e,t){function n(t,o){return e.exports=n=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},n(t,o)}e.exports=n},function(e,t,n){"use strict";n.r(t);var o=n(3),r=n.n(o),i=n(11),s=n.n(i),a=n(4),l=n.n(a),u=n(5),c=n.n(u),p=n(6),f=n.n(p),d=n(7),h=n.n(d),g=n(1),v=n.n(g),b=n(8),m=n.n(b),y=n(0),w=n(9),k=n(10),O=n.n(k),S=wp.url,L=S.getProtocol,j=S.isValidProtocol,E=S.getAuthority,C=S.isValidAuthority,P=S.getPath,x=S.isValidPath,_=S.getQueryString,N=S.isValidQueryString,R=S.getFragment,F=S.isValidFragment,T=wp.i18n,W=T.__,A=T.sprintf;function D(e){if(!e)return!1;var t=e.trim();if(!t)return!1;if(/^\S+:/.test(t)){var n=L(t);if(!j(n))return!1;if(Object(w.startsWith)(n,"http")&&!/^https?:\/\/[^\/\s]/i.test(t))return!1;var o=E(t);if(!C(o))return!1;var r=P(t);if(r&&!x(r))return!1;var i=_(t);if(i&&!N(i))return!1;var s=R(t);if(s&&!F(s))return!1}return!(Object(w.startsWith)(t,"#")&&!F(t))}function I(e){var t=e.url,n=e.opensInNewWindow,o=e.noFollow,r=e.sponsored,i=e.text,s={type:"aioseop/link",attributes:{url:t}},a=[];if(n){var l=A(W("%s (opens in a new tab)","all-in-one-seo-pack"),i);s.attributes.target="_blank",s.attributes["aria-label"]=l,a.push("noreferrer noopener")}return o&&a.push("nofollow"),r&&a.push("sponsored"),a.length>0&&(s.attributes.rel=a.join(" ")),s}var V=wp.element.Component,M=wp.dom,U=M.getOffsetParent,K=M.getRectangleFromRange;function B(){var e=window.getSelection();if(0===e.rangeCount)return{};var t=K(e.getRangeAt(0)),n=t.top+t.height,o=t.left+t.width/2,r=U(e.anchorNode);if(r){var i=r.getBoundingClientRect();n-=i.top,o-=i.left}return{top:n,left:o}}var H=function(e){function t(){var e;return l()(this,t),(e=f()(this,h()(t).apply(this,arguments))).state={style:B()},e}return m()(t,e),c()(t,[{key:"render",value:function(){var e=this.props.children,t=this.state.style;return Object(y.createElement)("div",{className:"editor-format-toolbar__selection-position",style:t},e)}}]),t}(V),z=n(2),q=n.n(z);function Q(e){return(Q="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function $(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function G(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function X(e,t){var n=e["page".concat(t?"Y":"X","Offset")],o="scroll".concat(t?"Top":"Left");if("number"!=typeof n){var r=e.document;"number"!=typeof(n=r.documentElement[o])&&(n=r.body[o])}return n}function Y(e){return X(e)}function J(e){return X(e,!0)}function Z(e){var t=function(e){var t,n,o,r=e.ownerDocument,i=r.body,s=r&&r.documentElement;return n=(t=e.getBoundingClientRect()).left,o=t.top,{left:n-=s.clientLeft||i.clientLeft||0,top:o-=s.clientTop||i.clientTop||0}}(e),n=e.ownerDocument,o=n.defaultView||n.parentWindow;return t.left+=Y(o),t.top+=J(o),t}var ee,te=new RegExp("^(".concat(/[\-+]?(?:\d*\.|)\d+(?:[eE][\-+]?\d+|)/.source,")(?!px)[a-z%]+$"),"i"),ne=/^(top|right|bottom|left)$/;function oe(e,t){for(var n=0;n<e.length;n++)t(e[n])}function re(e){return"border-box"===ee(e,"boxSizing")}"undefined"!=typeof window&&(ee=window.getComputedStyle?function(e,t,n){var o="",r=e.ownerDocument,i=n||r.defaultView.getComputedStyle(e,null);return i&&(o=i.getPropertyValue(t)||i[t]),o}:function(e,t){var n=e.currentStyle&&e.currentStyle[t];if(te.test(n)&&!ne.test(t)){var o=e.style,r=o.left,i=e.runtimeStyle.left;e.runtimeStyle.left=e.currentStyle.left,o.left="fontSize"===t?"1em":n||0,n=o.pixelLeft+"px",o.left=r,e.runtimeStyle.left=i}return""===n?"auto":n});var ie=["margin","border","padding"];function se(e,t,n){var o,r={},i=e.style;for(o in t)t.hasOwnProperty(o)&&(r[o]=i[o],i[o]=t[o]);for(o in n.call(e),t)t.hasOwnProperty(o)&&(i[o]=r[o])}function ae(e,t,n){var o,r,i,s=0;for(r=0;r<t.length;r++)if(o=t[r])for(i=0;i<n.length;i++){var a=void 0;a="border"===o?"".concat(o+n[i],"Width"):o+n[i],s+=parseFloat(ee(e,a))||0}return s}function le(e){return null!=e&&e==e.window}var ue={};function ce(e,t,n){if(le(e))return"width"===t?ue.viewportWidth(e):ue.viewportHeight(e);if(9===e.nodeType)return"width"===t?ue.docWidth(e):ue.docHeight(e);var o="width"===t?["Left","Right"]:["Top","Bottom"],r="width"===t?e.offsetWidth:e.offsetHeight,i=(ee(e),re(e)),s=0;(null==r||r<=0)&&(r=void 0,(null==(s=ee(e,t))||Number(s)<0)&&(s=e.style[t]||0),s=parseFloat(s)||0),void 0===n&&(n=i?1:-1);var a=void 0!==r||i,l=r||s;if(-1===n)return a?l-ae(e,["border","padding"],o):s;if(a){var u=2===n?-ae(e,["border"],o):ae(e,["margin"],o);return l+(1===n?0:u)}return s+ae(e,ie.slice(n),o)}oe(["Width","Height"],(function(e){ue["doc".concat(e)]=function(t){var n=t.document;return Math.max(n.documentElement["scroll".concat(e)],n.body["scroll".concat(e)],ue["viewport".concat(e)](n))},ue["viewport".concat(e)]=function(t){var n="client".concat(e),o=t.document,r=o.body,i=o.documentElement[n];return"CSS1Compat"===o.compatMode&&i||r&&r[n]||i}}));var pe={position:"absolute",visibility:"hidden",display:"block"};function fe(e){var t,n=arguments;return 0!==e.offsetWidth?t=ce.apply(void 0,n):se(e,pe,(function(){t=ce.apply(void 0,n)})),t}function de(e,t,n){var o=n;if("object"!==Q(t))return void 0!==o?("number"==typeof o&&(o+="px"),void(e.style[t]=o)):ee(e,t);for(var r in t)t.hasOwnProperty(r)&&de(e,r,t[r])}oe(["width","height"],(function(e){var t=e.charAt(0).toUpperCase()+e.slice(1);ue["outer".concat(t)]=function(t,n){return t&&fe(t,e,n?0:1)};var n="width"===e?["Left","Right"]:["Top","Bottom"];ue[e]=function(t,o){if(void 0===o)return t&&fe(t,e,-1);if(t){ee(t);return re(t)&&(o+=ae(t,["padding","border"],n)),de(t,e,o)}}}));var he=function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?G(n,!0).forEach((function(t){$(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):G(n).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}({getWindow:function(e){var t=e.ownerDocument||e;return t.defaultView||t.parentWindow},offset:function(e,t){if(void 0===t)return Z(e);!function(e,t){"static"===de(e,"position")&&(e.style.position="relative");var n,o,r=Z(e),i={};for(o in t)t.hasOwnProperty(o)&&(n=parseFloat(de(e,o))||0,i[o]=n+t[o]-r[o]);de(e,i)}(e,t)},isWindow:le,each:oe,css:de,clone:function(e){var t={};for(var n in e)e.hasOwnProperty(n)&&(t[n]=e[n]);if(e.overflow)for(var o in e)e.hasOwnProperty(o)&&(t.overflow[o]=e.overflow[o]);return t},scrollLeft:function(e,t){if(le(e)){if(void 0===t)return Y(e);window.scrollTo(t,J(e))}else{if(void 0===t)return e.scrollLeft;e.scrollLeft=t}},scrollTop:function(e,t){if(le(e)){if(void 0===t)return J(e);window.scrollTo(Y(e),t)}else{if(void 0===t)return e.scrollTop;e.scrollTop=t}},viewportWidth:0,viewportHeight:0},ue);var ge=function(e,t,n){n=n||{},9===t.nodeType&&(t=he.getWindow(t));var o=n.allowHorizontalScroll,r=n.onlyScrollIfNeeded,i=n.alignWithTop,s=n.alignWithLeft,a=n.offsetTop||0,l=n.offsetLeft||0,u=n.offsetBottom||0,c=n.offsetRight||0;o=void 0===o||o;var p,f,d,h,g,v,b,m,y,w,k=he.isWindow(t),O=he.offset(e),S=he.outerHeight(e),L=he.outerWidth(e);k?(b=t,w=he.height(b),y=he.width(b),m={left:he.scrollLeft(b),top:he.scrollTop(b)},g={left:O.left-m.left-l,top:O.top-m.top-a},v={left:O.left+L-(m.left+y)+c,top:O.top+S-(m.top+w)+u},h=m):(p=he.offset(t),f=t.clientHeight,d=t.clientWidth,h={left:t.scrollLeft,top:t.scrollTop},g={left:O.left-(p.left+(parseFloat(he.css(t,"borderLeftWidth"))||0))-l,top:O.top-(p.top+(parseFloat(he.css(t,"borderTopWidth"))||0))-a},v={left:O.left+L-(p.left+d+(parseFloat(he.css(t,"borderRightWidth"))||0))+c,top:O.top+S-(p.top+f+(parseFloat(he.css(t,"borderBottomWidth"))||0))+u}),g.top<0||v.top>0?!0===i?he.scrollTop(t,h.top+g.top):!1===i?he.scrollTop(t,h.top+v.top):g.top<0?he.scrollTop(t,h.top+g.top):he.scrollTop(t,h.top+v.top):r||((i=void 0===i||!!i)?he.scrollTop(t,h.top+g.top):he.scrollTop(t,h.top+v.top)),o&&(g.left<0||v.left>0?!0===s?he.scrollLeft(t,h.left+g.left):!1===s?he.scrollLeft(t,h.left+v.left):g.left<0?he.scrollLeft(t,h.left+g.left):he.scrollLeft(t,h.left+v.left):r||((s=void 0===s||!!s)?he.scrollLeft(t,h.left+g.left):he.scrollLeft(t,h.left+v.left)))},ve=wp.i18n,be=ve.__,me=ve.sprintf,ye=ve._n,we=wp.element,ke=we.Component,Oe=we.createRef,Se=wp.htmlEntities.decodeEntities,Le=wp.keycodes,je=Le.UP,Ee=Le.DOWN,Ce=Le.ENTER,Pe=Le.TAB,xe=wp.components,_e=xe.Spinner,Ne=xe.withSpokenMessages,Re=xe.Popover,Fe=wp.compose.withInstanceId,Te=wp.apiFetch,We=wp.url.addQueryArgs,Ae=function(e){return e.stopPropagation()},De=Ne(Fe(function(e){function t(e){var n,o=e.autocompleteRef;return l()(this,t),(n=f()(this,h()(t).apply(this,arguments))).onChange=n.onChange.bind(v()(n)),n.onKeyDown=n.onKeyDown.bind(v()(n)),n.autocompleteRef=o||Oe(),n.inputRef=Oe(),n.updateSuggestions=Object(w.throttle)(n.updateSuggestions.bind(v()(n)),200),n.suggestionNodes=[],n.state={posts:[],showSuggestions:!1,selectedSuggestion:null},n}return m()(t,e),c()(t,[{key:"componentDidUpdate",value:function(){var e=this,t=this.state,n=t.showSuggestions,o=t.selectedSuggestion;n&&null!==o&&!this.scrollingIntoView&&(this.scrollingIntoView=!0,ge(this.suggestionNodes[o],this.autocompleteRef.current,{onlyScrollIfNeeded:!0}),setTimeout((function(){e.scrollingIntoView=!1}),100))}},{key:"componentWillUnmount",value:function(){delete this.suggestionsRequest}},{key:"bindSuggestionNode",value:function(e){var t=this;return function(n){t.suggestionNodes[e]=n}}},{key:"updateSuggestions",value:function(e){var t=this;if(e.length<2||/^https?:/.test(e))this.setState({showSuggestions:!1,selectedSuggestion:null,loading:!1});else{this.setState({showSuggestions:!0,selectedSuggestion:null,loading:!0});var n=Te({path:We("/wp/v2/search",{search:e,per_page:20,type:"post"})});n.then((function(e){t.suggestionsRequest===n&&(t.setState({posts:e,loading:!1}),e.length?t.props.debouncedSpeak(me(ye("%d result found, use up and down arrow keys to navigate.","%d results found, use up and down arrow keys to navigate.",e.length),e.length),"assertive"):t.props.debouncedSpeak(be("No results.","all-in-one-seo-pack"),"assertive"))})).catch((function(){t.suggestionsRequest===n&&t.setState({loading:!1})})),this.suggestionsRequest=n}}},{key:"onChange",value:function(e){var t=e.target.value;this.props.onChange(t),this.updateSuggestions(t)}},{key:"onKeyDown",value:function(e){var t=this.state,n=t.showSuggestions,o=t.selectedSuggestion,r=t.posts,i=t.loading;if(n&&r.length&&!i){var s=this.state.posts[this.state.selectedSuggestion];switch(e.keyCode){case je:e.stopPropagation(),e.preventDefault();var a=o?o-1:r.length-1;this.setState({selectedSuggestion:a});break;case Ee:e.stopPropagation(),e.preventDefault();var l=null===o||o===r.length-1?0:o+1;this.setState({selectedSuggestion:l});break;case Pe:null!==this.state.selectedSuggestion&&(this.selectLink(s),this.props.speak(be("Link selected.","all-in-one-seo-pack")));break;case Ce:null!==this.state.selectedSuggestion&&(e.stopPropagation(),this.selectLink(s))}}else switch(e.keyCode){case je:0!==e.target.selectionStart&&(e.stopPropagation(),e.preventDefault(),e.target.setSelectionRange(0,0));break;case Ee:this.props.value.length!==e.target.selectionStart&&(e.stopPropagation(),e.preventDefault(),e.target.setSelectionRange(this.props.value.length,this.props.value.length))}}},{key:"selectLink",value:function(e){this.props.onChange(e.url,e),this.setState({selectedSuggestion:null,showSuggestions:!1})}},{key:"handleOnClick",value:function(e){this.selectLink(e),this.inputRef.current.focus()}},{key:"render",value:function(){var e=this,t=this.props,n=t.value,o=void 0===n?"":n,r=t.autoFocus,i=void 0===r||r,s=t.instanceId,a=t.className,l=this.state,u=l.showSuggestions,c=l.posts,p=l.selectedSuggestion,f=l.loading;return Object(y.createElement)("div",{className:q()("editor-url-input block-editor-url-input",a)},Object(y.createElement)("input",{autoFocus:i,type:"text","aria-label":be("URL","all-in-one-seo-pack"),required:!0,value:o,onChange:this.onChange,onInput:Ae,placeholder:be("Paste URL or type to search","all-in-one-seo-pack"),onKeyDown:this.onKeyDown,role:"combobox","aria-expanded":u,"aria-autocomplete":"list","aria-owns":"editor-url-input-suggestions-".concat(s),"aria-activedescendant":null!==p?"editor-url-input-suggestion-".concat(s,"-").concat(p):void 0,ref:this.inputRef}),f&&Object(y.createElement)(_e,null),u&&!!c.length&&Object(y.createElement)(Re,{position:"bottom",noArrow:!0,focusOnMount:!1},Object(y.createElement)("div",{className:q()("editor-url-input__suggestions","block-editor-url-input__suggestions","".concat(a,"__suggestions")),id:"editor-url-input-suggestions-".concat(s),ref:this.autocompleteRef,role:"listbox"},c.map((function(t,n){return Object(y.createElement)("button",{key:t.id,role:"option",tabIndex:"-1",id:"editor-url-input-suggestion-".concat(s,"-").concat(n),ref:e.bindSuggestionNode(n),className:q()("editor-url-input__suggestion block-editor-url-input__suggestion",{"is-selected":n===p}),onClick:function(){return e.handleOnClick(t)},"aria-selected":n===p},Se(t.title)||be("(no title)","all-in-one-seo-pack"))})))))}}]),t}(ke))),Ie=wp.i18n.__,Ve=wp.components.IconButton;function Me(e){var t=e.autocompleteRef,n=e.className,o=e.onChangeInputValue,i=e.value,s=r()(e,["autocompleteRef","className","onChangeInputValue","value"]);return Object(y.createElement)("form",O()({className:q()("block-editor-url-popover__link-editor",n)},s),Object(y.createElement)(De,{value:i,onChange:o,autocompleteRef:t}),Object(y.createElement)(Ve,{icon:"editor-break",label:Ie("Apply","all-in-one-seo-pack"),type:"submit"}))}var Ue=wp.i18n.__,Ke=wp.components,Be=Ke.ExternalLink,He=Ke.IconButton,ze=wp.url,qe=ze.safeDecodeURI,Qe=ze.filterURLForDisplay;function $e(e){var t=e.url,n=e.urlLabel,o=e.className,r=q()(o,"block-editor-url-popover__link-viewer-url");return t?Object(y.createElement)(Be,{className:r,href:t},n||Qe(qe(t))):Object(y.createElement)("span",{className:r})}function Ge(e){var t=e.className,n=e.linkClassName,o=e.onEditLinkClick,i=e.url,s=e.urlLabel,a=r()(e,["className","linkClassName","onEditLinkClick","url","urlLabel"]);return Object(y.createElement)("div",O()({className:q()("block-editor-url-popover__link-viewer",t)},a),Object(y.createElement)($e,{url:i,urlLabel:s,className:n}),o&&Object(y.createElement)(He,{icon:"edit",label:Ue("Edit","all-in-one-seo-pack"),onClick:o}))}var Xe=wp.i18n.__,Ye=wp.element,Je=Ye.Component,Ze=Ye.createRef,et=Ye.useMemo,tt=Ye.Fragment,nt=wp.components,ot=nt.ToggleControl,rt=nt.withSpokenMessages,it=wp.keycodes,st=it.LEFT,at=it.RIGHT,lt=it.UP,ut=it.DOWN,ct=it.BACKSPACE,pt=it.ENTER,ft=it.ESCAPE,dt=wp.dom.getRectangleFromRange,ht=wp.url.prependHTTP,gt=wp.richText,vt=gt.create,bt=gt.insert,mt=gt.isCollapsed,yt=gt.applyFormat,wt=gt.getTextContent,kt=gt.slice,Ot=wp.blockEditor.URLPopover,St=function(e){return e.stopPropagation()};function Lt(e,t){return e.addingLink||t.editLink}var jt=function(e){var t=e.isActive,n=e.addingLink,o=e.value,i=e.resetOnMount,s=r()(e,["isActive","addingLink","value","resetOnMount"]),a=et((function(){var e=window.getSelection(),t=e.rangeCount>0?e.getRangeAt(0):null;if(t){if(n)return dt(t);var o=t.startContainer;for(o=o.nextElementSibling||o;o.nodeType!==window.Node.ELEMENT_NODE;)o=o.parentNode;var r=o.closest("a");return r?r.getBoundingClientRect():void 0}}),[t,n,o.start,o.end]);return a?(i(a),Object(y.createElement)(Ot,O()({anchorRect:a},s))):null},Et=rt(function(e){function t(){var e;return l()(this,t),(e=f()(this,h()(t).apply(this,arguments))).editLink=e.editLink.bind(v()(e)),e.submitLink=e.submitLink.bind(v()(e)),e.onKeyDown=e.onKeyDown.bind(v()(e)),e.onChangeInputValue=e.onChangeInputValue.bind(v()(e)),e.setLinkTarget=e.setLinkTarget.bind(v()(e)),e.setNoFollow=e.setNoFollow.bind(v()(e)),e.setSponsored=e.setSponsored.bind(v()(e)),e.onFocusOutside=e.onFocusOutside.bind(v()(e)),e.resetState=e.resetState.bind(v()(e)),e.autocompleteRef=Ze(),e.resetOnMount=e.resetOnMount.bind(v()(e)),e.state={opensInNewWindow:!1,noFollow:!1,sponsored:!1,inputValue:"",anchorRect:!1},e}return m()(t,e),c()(t,[{key:"onKeyDown",value:function(e){[st,ut,at,lt,ct,pt].indexOf(e.keyCode)>-1&&e.stopPropagation(),[ft].indexOf(e.keyCode)>-1&&this.resetState()}},{key:"onChangeInputValue",value:function(e){this.setState({inputValue:e})}},{key:"setLinkTarget",value:function(e){var t=this.props,n=t.activeAttributes.url,o=void 0===n?"":n,r=t.value,i=t.onChange;if(this.setState({opensInNewWindow:e}),!Lt(this.props,this.state)){var s=wt(kt(r));i(yt(r,I({url:o,opensInNewWindow:e,noFollow:this.state.noFollow,sponsored:this.state.sponsored,text:s})))}}},{key:"setNoFollow",value:function(e){var t=this.props,n=t.activeAttributes.url,o=void 0===n?"":n,r=t.value,i=t.onChange;if(this.setState({noFollow:e}),!Lt(this.props,this.state)){var s=wt(kt(r));i(yt(r,I({url:o,opensInNewWindow:this.state.opensInNewWindow,noFollow:e,sponsored:this.state.sponsored,text:s})))}}},{key:"setSponsored",value:function(e){var t=this.props,n=t.activeAttributes.url,o=void 0===n?"":n,r=t.value,i=t.onChange;if(this.setState({sponsored:e}),!Lt(this.props,this.state)){var s=wt(kt(r));i(yt(r,I({url:o,opensInNewWindow:this.state.opensInNewWindow,noFollow:this.state.noFollow,sponsored:e,text:s})))}}},{key:"editLink",value:function(e){this.setState({editLink:!0}),e.preventDefault()}},{key:"submitLink",value:function(e){var t=this.props,n=t.isActive,o=t.value,r=t.onChange,i=t.speak,s=this.state,a=s.inputValue,l=s.opensInNewWindow,u=s.noFollow,c=s.sponsored,p=ht(a),f=I({url:p,opensInNewWindow:l,noFollow:u,sponsored:c,text:wt(kt(o))});if(e.preventDefault(),mt(o)&&!n){var d=yt(vt({text:p}),f,0,p.length);r(bt(o,d))}else r(yt(o,f));this.resetState(),D(p)?i(Xe(n?"Link edited.":"Link inserted.","all-in-one-seo-pack"),"assertive"):i(Xe("Warning: the link has been inserted but could have errors. Please test it.","all-in-one-seo-pack"),"assertive")}},{key:"onFocusOutside",value:function(){var e=this.autocompleteRef.current;e&&e.contains(event.target)||this.resetState()}},{key:"resetState",value:function(){this.props.stopAddingLink(),this.setState({editLink:!1})}},{key:"resetOnMount",value:function(e){this.state.anchorRect!==e&&this.setState({opensInNewWindow:!1,noFollow:!1,sponsored:!1,anchorRect:e})}},{key:"render",value:function(){var e=this,t=this.props,n=t.isActive,o=t.activeAttributes,r=o.url,i=o.target,s=o.rel,a=t.addingLink,l=t.value;if(!n&&!a)return null;var u=this.state,c=u.inputValue,p=u.opensInNewWindow,f=u.noFollow,d=u.sponsored,h=Lt(this.props,this.state);if(p||"_blank"!==i||this.setState({opensInNewWindow:!0}),"string"==typeof s){var g=s.split(" ").includes("nofollow"),v=s.split(" ").includes("sponsored");g!==f&&this.setState({noFollow:g}),v!==d&&this.setState({sponsored:v})}return Object(y.createElement)(H,{key:"".concat(l.start).concat(l.end)},Object(y.createElement)(jt,{resetOnMount:this.resetOnMount,value:l,isActive:n,addingLink:a,onFocusOutside:this.onFocusOutside,onClose:function(){c||e.resetState()},focusOnMount:!!h&&"firstElement",renderSettings:function(){return Object(y.createElement)(tt,null,Object(y.createElement)(ot,{label:Xe("Open in New Tab","all-in-one-seo-pack"),checked:p,onChange:e.setLinkTarget}),Object(y.createElement)(ot,{label:Xe('Add "nofollow" to link',"all-in-one-seo-pack"),checked:f,onChange:e.setNoFollow}),Object(y.createElement)(ot,{label:Xe('Add "sponsored" to link',"all-in-one-seo-pack"),checked:d,onChange:e.setSponsored}))}},h?Object(y.createElement)(Me,{className:"editor-format-toolbar__link-container-content block-editor-format-toolbar__link-container-content",value:c,onChangeInputValue:this.onChangeInputValue,onKeyDown:this.onKeyDown,onKeyPress:St,onSubmit:this.submitLink,autocompleteRef:this.autocompleteRef}):Object(y.createElement)(Ge,{className:"editor-format-toolbar__link-container-content block-editor-format-toolbar__link-container-content",onKeyPress:St,url:r,onEditLinkClick:this.editLink,linkClassName:D(ht(r))?void 0:"has-invalid-link"})))}}],[{key:"getDerivedStateFromProps",value:function(e,t){var n=e.activeAttributes,o=n.url,r=n.target,i=n.rel,s="_blank"===r;if(!Lt(e,t)){if(o!==t.inputValue)return{inputValue:o};if(s!==t.opensInNewWindow)return{opensInNewWindow:s};if("string"==typeof i){var a=i.split(" ").includes("nofollow"),l=i.split(" ").includes("sponsored");if(a!==t.noFollow)return{noFollow:a};if(l!==t.sponsored)return{sponsored:l}}}return null}}]),t}(Je));function Ct(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function Pt(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?Ct(Object(n),!0).forEach((function(t){s()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):Ct(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var xt=wp.i18n.__,_t=wp.element,Nt=_t.Component,Rt=_t.Fragment,Ft=wp.data,Tt=Ft.select,Wt=Ft.withSelect,At=Ft.dispatch,Dt=wp.blockEditor,It=Dt.BlockControls,Vt=Dt.RichTextToolbarButton,Mt=Dt.RichTextShortcut,Ut=wp.richText,Kt=Ut.getTextContent,Bt=Ut.applyFormat,Ht=Ut.removeFormat,zt=Ut.slice,qt=Ut.getActiveFormat,Qt=wp.url.isURL,$t=wp.components,Gt=$t.Toolbar,Xt=$t.withSpokenMessages,Yt=wp.compose,Jt=Yt.compose,Zt=Yt.ifCondition,en=xt("Add Link","all-in-one-seo-pack"),tn=/^(mailto:)?[a-z0-9._%+-]+@[a-z0-9][a-z0-9.-]*\.[a-z]{2,63}$/i,nn=function(e){function t(){var e;return l()(this,t),(e=f()(this,h()(t).apply(this,arguments))).isEmail=e.isEmail.bind(v()(e)),e.addLink=e.addLink.bind(v()(e)),e.stopAddingLink=e.stopAddingLink.bind(v()(e)),e.onRemoveFormat=e.onRemoveFormat.bind(v()(e)),e.state={addingLink:!1},e}return m()(t,e),c()(t,[{key:"componentDidMount",value:function(){var e=Tt("core/rich-text").getFormatType("core/link");e&&(e.edit=null,At("core/rich-text").addFormatTypes(e))}},{key:"isEmail",value:function(e){return tn.test(e)}},{key:"addLink",value:function(){var e=this.props,t=e.value,n=e.onChange,o=Kt(zt(t));o&&Qt(o)?n(Bt(t,{type:"aioseop/link",attributes:{url:o}})):o&&this.isEmail(o)?n(Bt(t,{type:"aioseop/link",attributes:{url:"mailto:".concat(o)}})):this.setState({addingLink:!0})}},{key:"stopAddingLink",value:function(){this.setState({addingLink:!1})}},{key:"onRemoveFormat",value:function(){var e=this.props,t=e.value,n=e.onChange,o=e.speak,r=t;Object(w.map)(["core/link","aioseop/link"],(function(e){r=Ht(r,e)})),n(Pt({},r)),o(xt("Link removed.","all-in-one-seo-pack"),"assertive")}},{key:"render",value:function(){var e=this.props,t=e.activeAttributes,n=e.onChange,o=this.props,r=o.isActive,i=o.value,s=qt(i,"core/link");if(s){s.type="aioseop/link";var a=i;a=Bt(a,s),n(Pt({},a=Ht(a,"core/link"))),i=a,r=!0}return Object(y.createElement)(Rt,null,Object(y.createElement)(It,null,Object(y.createElement)(Gt,{className:"editorskit-components-toolbar"},Object(y.createElement)(Mt,{type:"primary",character:"k",onUse:this.addLink}),Object(y.createElement)(Mt,{type:"primaryShift",character:"k",onUse:this.onRemoveFormat}),r&&Object(y.createElement)(Vt,{name:"link",icon:"editor-unlink",title:xt("Unlink","all-in-one-seo-pack"),onClick:this.onRemoveFormat,isActive:r,shortcutType:"primaryShift",shortcutCharacter:"k"}),!r&&Object(y.createElement)(Vt,{name:"link",icon:"admin-links",title:en,onClick:this.addLink,isActive:r,shortcutType:"primary",shortcutCharacter:"k"}),Object(y.createElement)(Et,{addingLink:this.state.addingLink,stopAddingLink:this.stopAddingLink,isActive:r,activeAttributes:t,value:i,onChange:n}))))}}]),t}(Nt),on=Jt(Wt((function(){return{isDisabled:Tt("core/edit-post").isFeatureActive("disableEditorsKitLinkFormats")}})),Zt((function(e){return!e.isDisabled})),Xt)(nn);n.d(t,"link",(function(){return fn}));var rn=wp.i18n.__,sn=wp.richText,an=sn.registerFormatType,ln=sn.applyFormat,un=sn.isCollapsed,cn=wp.htmlEntities.decodeEntities,pn=wp.url.isURL,fn={name:"aioseop/link",title:rn("Link","all-in-one-seo-pack"),tagName:"a",className:"aioseop-link",attributes:{url:"href",target:"target",rel:"rel"},__unstablePasteRule:function(e,t){var n=t.html,o=t.plainText;if(un(e))return e;var r=(n||o).replace(/<[^>]+>/g,"").trim();return pn(r)?ln(e,{type:"aioseop/link",attributes:{url:cn(r)}}):e},edit:on};[fn].forEach((function(e){var t=e.name,n=r()(e,["name"]);t&&an(t,n)}))}]);