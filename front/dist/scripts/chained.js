!function(t){"function"==typeof define&&define.amd?define(["jquery"],t):t(window.jQuery||window.Zepto||window.$)}(function(t){var e=function(){for(var a,n,s,i=0,r=arguments.length;i<r;i++)e.isJquery(arguments[i])||e.isZepto(arguments[i])?a=arguments[i]:e.isElement(arguments[i])?a=t(arguments[i]):"function"==typeof arguments[i]?s=arguments[i]:"object"==typeof arguments[i]&&(n=arguments[i]);var l=new e.init(a,n);return"function"==typeof s&&s(l),l};e.isElement=function(t){return!(!t||"function"!=typeof HTMLElement&&"object"!=typeof HTMLElement||!(t instanceof HTMLElement))||!(!t||!t.nodeType||1!==t.nodeType)},e.isJquery=function(t){return!!(t&&t.length&&("function"==typeof jQuery||"object"==typeof jQuery)&&t instanceof jQuery)},e.isZepto=function(t){return!(!t||!t.length||"function"!=typeof Zepto&&"object"!=typeof Zepto||!Zepto.zepto.isZ(t))},e.getIndex=function(t,e){return e?t:t-1},e.getData=function(t,e){if("string"==typeof e&&e.length){e=e.split(".");for(var a=0,n=e.length;a<n;a++)t=t[e[a]]}return t},e.init=function(a,n){var s=this;if(e.isJquery(a)||e.isZepto(a)){var i={dom:{box:a}};s.attach=e.attach.bind(i),s.detach=e.detach.bind(i),s.setOptions=e.setOptions.bind(i),s.clear=e.clear.bind(i),i.changeEvent=function(){e.selectChange.call(i,this.className)},i.settings=t.extend({},t.cxSelect.defaults,n,{url:i.dom.box.data("url"),emptyStyle:i.dom.box.data("emptyStyle"),required:i.dom.box.data("required"),firstTitle:i.dom.box.data("firstTitle"),firstValue:i.dom.box.data("firstValue"),jsonSpace:i.dom.box.data("jsonSpace"),jsonName:i.dom.box.data("jsonName"),jsonValue:i.dom.box.data("jsonValue"),jsonSub:i.dom.box.data("jsonSub")});var r=i.dom.box.data("selects");"string"==typeof r&&r.length&&(i.settings.selects=r.split(",")),s.setOptions(),s.attach(),i.settings.url||i.settings.data?t.isArray(i.settings.data)?e.start.call(i,i.settings.data):"string"==typeof i.settings.url&&i.settings.url.length&&t.getJSON(i.settings.url,function(t){e.start.call(i,t)}):e.start.apply(i)}},e.setOptions=function(a){var n=this;if(a&&t.extend(n.settings,a),(!t.isArray(n.selectArray)||!n.selectArray.length||a&&a.selects)&&(n.selectArray=[],t.isArray(n.settings.selects)&&n.settings.selects.length))for(var s,i=0,r=n.settings.selects.length;i<r&&((s=n.dom.box.find("select."+n.settings.selects[i]))&&s.length);i++)n.selectArray.push(s);a&&(!t.isArray(a.data)&&"string"==typeof a.url&&a.url.length?t.getJSON(n.settings.url,function(t){e.start.call(n,t)}):e.start.call(n,a.data))},e.attach=function(){var t=this;t.attachStatus||t.dom.box.on("change","select",t.changeEvent),"boolean"==typeof t.attachStatus&&e.start.call(t),t.attachStatus=!0},e.detach=function(){var t=this;t.dom.box.off("change","select",t.changeEvent),t.attachStatus=!1},e.clear=function(t){var e=this,a={display:"",visibility:""};t=isNaN(t)?0:t;for(var n=t,s=e.selectArray.length;n<s;n++)e.selectArray[n].empty().prop("disabled",!0),"none"===e.settings.emptyStyle?a.display="none":"hidden"===e.settings.emptyStyle&&(a.visibility="hidden"),e.selectArray[n].css(a)},e.start=function(a){var n=this;if(t.isArray(a)&&(n.settings.data=e.getData(a,n.settings.jsonSpace)),n.selectArray.length){for(var s=0,i=n.selectArray.length;s<i;s++)"string"!=typeof n.selectArray[s].attr("data-value")&&n.selectArray[s][0].options.length&&n.selectArray[s].attr("data-value",n.selectArray[s].val());n.settings.data||"string"==typeof n.selectArray[0].data("url")&&n.selectArray[0].data("url").length?e.getOptionData.call(n,0):n.selectArray[0][0].options.length&&"string"==typeof n.selectArray[0].attr("data-value")?(n.selectArray[0].val(n.selectArray[0].attr("data-value")),e.getOptionData.call(n,1)):n.selectArray[0].prop("disabled",!1).css({display:"",visibility:""})}},e.getOptionData=function(a){var n=this;if(!("number"!=typeof a||isNaN(a)||a<0||a>=n.selectArray.length)){var s,i,r,l,o,c=n.selectArray[a],d=c.data("url"),u=void 0===c.data("jsonSpace")?n.settings.jsonSpace:c.data("jsonSpace"),f={};if(e.clear.call(n,a),"string"==typeof d&&d.length){if(a>0)for(var g=0,y=1;g<a;g++,y++)r=n.selectArray[y].data("queryName"),l=n.selectArray[g].attr("name"),o=n.selectArray[g].val(),"string"==typeof r&&r.length?f[r]=o:"string"==typeof l&&l.length&&(f[l]=o);t.getJSON(d,f,function(t){s=e.getData(t,u),e.buildOption.call(n,a,s)})}else if(n.settings.data&&"object"==typeof n.settings.data){s=n.settings.data;for(var g=0;g<a;g++){if(i=e.getIndex(n.selectArray[g][0].selectedIndex,"boolean"==typeof n.selectArray[g].data("required")?n.selectArray[g].data("required"):n.settings.required),"object"!=typeof s[i]||!t.isArray(s[i][n.settings.jsonSub])||!s[i][n.settings.jsonSub].length){s=null;break}s=s[i][n.settings.jsonSub]}e.buildOption.call(n,a,s)}}},e.buildOption=function(e,a){var n=this,s=n.selectArray[e],i="boolean"==typeof s.data("required")?s.data("required"):n.settings.required,r=void 0===s.data("firstTitle")?n.settings.firstTitle:s.data("firstTitle"),l=void 0===s.data("firstValue")?n.settings.firstValue:s.data("firstValue"),o=void 0===s.data("jsonName")?n.settings.jsonName:s.data("jsonName"),c=void 0===s.data("jsonValue")?n.settings.jsonValue:s.data("jsonValue");if(t.isArray(a)){var d=i?"":'<option value="'+String(l)+'">'+String(r)+"</option>";if("string"==typeof o&&o.length){"string"==typeof c&&c.length||(c=o);for(var u=0,f=a.length;u<f;u++)d+='<option value="'+String(a[u][c])+'">'+String(a[u][o])+"</option>"}else for(var u=0,f=a.length;u<f;u++)d+='<option value="'+String(a[u])+'">'+String(a[u])+"</option>";s.html(d).prop("disabled",!1).css({display:"",visibility:""}),"string"==typeof s.attr("data-value")&&(s.val(String(s.attr("data-value"))).removeAttr("data-value"),s[0].selectedIndex<0&&(s[0].options[0].selected=!0)),(i||s[0].selectedIndex>0)&&s.trigger("change")}},e.selectChange=function(t){var a=this;if("string"==typeof t&&t.length){var n;t=t.replace(/\s+/g,","),t=","+t+",";for(var s=0,i=a.selectArray.length;s<i;s++)if(t.indexOf(","+a.settings.selects[s]+",")>-1){n=s;break}"number"==typeof n&&n>-1&&(n+=1,e.getOptionData.call(a,n))}},t.cxSelect=function(){return e.apply(this,arguments)},t.cxSelect.defaults={selects:[],url:null,data:null,emptyStyle:null,required:!1,firstTitle:"请选择",firstValue:"",jsonSpace:"",jsonName:"n",jsonValue:"",jsonSub:"s"},t.fn.cxSelect=function(e,a){return this.each(function(n){t.cxSelect(this,e,a)}),this}});
//# sourceMappingURL=chained.js.map
