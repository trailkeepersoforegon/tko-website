import{a as v,d as B,m as C}from"./vuex.esm-bundler.8589b2dd.js";import{W as D}from"./WpTable.4d19dc46.js";import"./default-i18n.ab92175e.js";import"./constants.7044c894.js";import{_ as b,r as d,o as a,c as p,d as u,t as r,a as f,w as i,f as l,g as x,e as h,h as g}from"./_plugin-vue_export-helper.2d9794a3.js";import{D as P}from"./index.02a5ed9a.js";import"./SaveChanges.bc66cd69.js";import{P as F}from"./PostTypes.9ab32454.js";import{V as T,S as A,T as O,c as U}from"./Statistic.6583e19d.js";import{C as I}from"./ScoreButton.e74a21e9.js";import{C as V}from"./Table.1a0736e7.js";import{C as L}from"./Index.a5b2ee90.js";const N={components:{apexchart:T},props:{points:{type:Object,required:!0},peak:{type:Number,default(){return 0}},recovering:{type:Boolean,default(){return!1}},height:{type:Number,default(){return 50}}},data(){return{strings:{recovering:this.$t.__("Slowly Recovering",this.$td),peak:this.$t.__("Peak",this.$td)}}},computed:{getSeries(){const e=this.points,o=[];return Object.keys(e).forEach(s=>{o.push({x:s,y:e[s]})}),[{data:o}]},chartOptions(){const e=this.peak;return{colors:[function({value:o}){return o===e?"#005AE0":"#99C2FF"}],chart:{type:"bar",sparkline:{enabled:!0},zoom:{enabled:!1},toolbar:{show:!1},parentHeightOffset:0,background:"#fff"},grid:{show:!1,padding:{top:2,right:2,bottom:0,left:2}},plotOptions:{bar:{columnWidth:"85%",barHeight:"100%"}},fill:{type:"solid"},tooltip:{enabled:!0,x:{show:!0,formatter:o=>P.fromFormat(o,"yyyy-MM").setZone(P.zone).toLocaleString({month:"long",year:"numeric"})},y:{formatter:o=>{const s=this.$t.sprintf(this.$t.__("%1$s points",this.$td),this.$numbers.numberFormat(o,0));let c="";return o===e&&(c=`<span class="peak">${this.strings.peak}</span>`),s+c}},marker:{show:!1}}}}}},H={class:"aioseo-graph-decay"},M={key:0,class:"aioseo-graph-decay-recovering"};function j(e,o,s,c,n,m){const y=d("apexchart");return a(),p("div",H,[u(y,{width:"100%",height:s.height,ref:"apexchart",options:m.chartOptions,series:m.getSeries,class:"aioseo-graph-decay-chart"},null,8,["height","options","series"]),s.recovering?(a(),p("div",M,r(n.strings.recovering),1)):f("",!0)])}const E=b(N,[["render",j]]),W={};function G(e,o){return a(),p("div")}const R=b(W,[["render",G]]);const Z={components:{CoreScoreButton:I,CoreWpTable:V,Cta:L,GraphDecay:E,PostActions:R,Statistic:A},mixins:[F,D,O],data(){return{tableId:"aioseo-search-statistics-post-table",changeItemsPerPageSlug:"searchStatisticsSeoStatistics",showUpsell:!1,strings:{ctaButtonText:this.$t.__("Upgrade to Pro and Unlock Access Control",this.$td),ctaHeader:this.$t.sprintf(this.$t.__("Access Control is only available for licensed %1$s %2$s users.",this.$td),"AIOSEO","Pro")}}},props:{posts:Object,isLoading:Boolean,showHeader:{type:Boolean,default(){return!0}},showTableFooter:Boolean,showItemsPerPage:Boolean,disableSorting:Boolean,columns:{type:Array,default(){return["post_title","seo_score","clicks","impressions","position"]}},appendColumns:{type:Object,default(){return{}}},initialFilter:{type:String,default(){return""}},updateAction:{type:String,default(){return"updateSeoStatistics"}}},computed:{...v("search-statistics",["data","loading"]),...B(["isUnlicensed"]),allColumns(){var s,c;const e=U(this.columns),o=((c=(s=this.posts)==null?void 0:s.filters)==null?void 0:c.find(n=>n.active))||{};return this.appendColumns[o.slug||"all"]&&e.push(this.appendColumns[o.slug||"all"]),e},tableColumns(){return[{slug:"row",label:"#",width:"40px"},{slug:"post_title",label:this.$t.__("Title",this.$td),width:"100%",sortDir:this.orderBy==="post_title"?this.orderDir:"asc",sorted:this.orderBy==="post_title"},{slug:"seo_score",label:this.$t.__("TruSEO Score",this.$td),width:"130px",sortDir:this.orderBy==="seo_score"?this.orderDir:"asc",sorted:this.orderBy==="seo_score"},{slug:"clicks",label:this.$t.__("Clicks",this.$td),width:"80px",sortable:this.isSortable,sortDir:this.orderBy==="clicks"?this.orderDir:"asc",sorted:this.orderBy==="clicks"},{slug:"impressions",label:this.$t.__("Impressions",this.$td),width:"110px",sortable:this.isSortable,sortDir:this.orderBy==="impressions"?this.orderDir:"asc",sorted:this.orderBy==="impressions"},{slug:"position",label:this.$t.__("Position",this.$td),width:"90px",sortable:this.isSortable,sortDir:this.orderBy==="position"?this.orderDir:"asc",sorted:this.orderBy==="position"},{slug:"lastUpdated",label:this.$t.__("Last Updated On",this.$td),width:"160px",sortDir:this.orderBy==="lastUpdated"?this.orderDir:"asc",sorted:this.orderBy==="lastUpdated"},{slug:"decay",label:this.$t.__("Loss",this.$td),width:"140px",sortable:this.isSortable,sortDir:this.orderBy==="decay"?this.orderDir:"asc",sorted:this.orderBy==="decay"},{slug:"decayPercent",label:this.$t.__("Drop (%)",this.$td),width:"120px",sortable:this.isSortable,sortDir:this.orderBy==="decayPercent"?this.orderDir:"asc",sorted:this.orderBy==="decayPercent"},{slug:"performance",label:this.$t.__("Performance Score",this.$td),width:"150px"},{slug:"diffDecay",label:this.$t.__("Diff",this.$tdPro),width:"95px"},{slug:"diffPosition",label:this.$t.__("Diff",this.$tdPro),width:"80px"}].filter(e=>this.allColumns.includes(e.slug))},isSortable(){return this.disableSorting?!1:this.filter==="all"&&this.$isPro&&!this.isUnlicensed}},methods:{...C("search-statistics",["updateSeoStatistics","updateContentRankings"]),resetSelectedFilters(){this.selectedFilters.postType="",this.processAdditionaFilterOptionSelected({name:"postType",selectedValue:""})},fetchData(e){if(typeof this[this.updateAction]=="function")return this[this.updateAction](e)}},mounted(){this.initialFilter&&this.processFilter({slug:this.initialFilter})}},z={class:"aioseo-search-statistics-post-table"},q={class:"post-row"},J={class:"post-title"},K=["onClick"],Q={key:1,class:"post-title"},X={key:0,class:"row-actions"},Y=["href"],tt=["href"];function et(e,o,s,c,n,m){const y=d("post-actions"),k=d("core-score-button"),_=d("statistic"),S=d("graph-decay"),w=d("cta"),$=d("core-wp-table");return a(),p("div",z,[u($,{ref:"table",class:"posts-table",id:n.tableId,columns:m.tableColumns,rows:Object.values(s.posts.rows),totals:s.posts.totals,filters:s.posts.filters,"additional-filters":s.posts.additionalFilters,"selected-filters":e.selectedFilters,loading:s.isLoading||e.loading.seoStatistics,"initial-page-number":e.pageNumber,"initial-search-term":e.searchTerm,"initial-items-per-page":e.$aioseo.settings.tablePagination[n.changeItemsPerPageSlug],"show-header":s.showHeader,"show-bulk-actions":!1,"show-table-footer":s.showTableFooter,"show-items-per-page":s.showItemsPerPage,"show-pagination":"","blur-rows":n.showUpsell,onFilterTable:e.processFilter,onProcessAdditionalFilters:e.processAdditionalFilters,onAdditionalFilterOptionSelected:e.processAdditionaFilterOptionSelected,onPaginate:e.processPagination,onProcessChangeItemsPerPage:e.processChangeItemsPerPage,onSearch:e.processSearch,onSortColumn:e.processSort},{row:i(({index:t})=>[l("div",q,r(t+1),1)]),post_title:i(({row:t})=>[l("div",J,[t.postId?(a(),p("a",{key:0,href:"#",onClick:x(st=>e.openPostDetail(t),["prevent"])},r(t.postTitle),9,K)):(a(),p("span",Q,r(t.postTitle),1))]),u(y,{row:t},null,8,["row"]),t.postId?(a(),p("div",X,[l("span",null,[l("a",{class:"view",href:t.context.permalink,target:"_blank"},[l("span",null,r(e.viewPost(t.context.postType.singular)),1)],8,Y),h(" | ")]),l("span",null,[l("a",{class:"edit",href:t.context.editLink,target:"_blank"},[l("span",null,r(e.editPost(t.context.postType.singular)),1)],8,tt)])])):f("",!0)]),seo_score:i(({row:t})=>[t.seoScore?(a(),g(k,{key:0,class:"table-score-button",score:t.seoScore},null,8,["score"])):f("",!0)]),clicks:i(({row:t})=>[h(r(e.$numbers.compactNumber(t.clicks)),1)]),impressions:i(({row:t})=>[h(r(e.$numbers.compactNumber(t.impressions)),1)]),position:i(({row:t})=>[h(r(Math.round(t.position).toFixed(0)),1)]),lastUpdated:i(({row:t})=>[h(r(t.context.lastUpdated||"-"),1)]),decay:i(({row:t})=>[u(_,{type:"decay","show-difference":!1,total:t.decay,showZeroValues:!0,class:"no-margin"},null,8,["total"])]),decayPercent:i(({row:t})=>[u(_,{type:"decayPercent","show-difference":!1,total:t.decayPercent,showZeroValues:!0,class:"no-margin"},null,8,["total"])]),performance:i(({row:t})=>[u(S,{points:t.points,peak:t.peak,recovering:t.recovering,height:38},null,8,["points","peak","recovering"])]),diffPosition:i(({row:t})=>[t.difference.comparison?(a(),g(_,{key:0,type:"position","show-original":!1,difference:t.difference.position,"tooltip-offset":"-100px,0"},null,8,["difference"])):f("",!0)]),diffDecay:i(({row:t})=>[t.difference.comparison?(a(),g(_,{key:0,type:"diffDecay","show-original":!1,difference:t.difference.decay,"tooltip-offset":"-100px,0"},null,8,["difference"])):f("",!0)]),cta:i(()=>[n.showUpsell?(a(),g(w,{key:0,"cta-link":e.$links.getPricingUrl("search-statistics","search-statistics-upsell"),"button-text":n.strings.ctaButtonText,"learn-more-link":e.$links.getUpsellUrl("search-statistics","search-statistics-upsell","home")},{"header-text":i(()=>[h(r(n.strings.ctaHeader),1)]),_:1},8,["cta-link","button-text","learn-more-link"])):f("",!0)]),_:1},8,["id","columns","rows","totals","filters","additional-filters","selected-filters","loading","initial-page-number","initial-search-term","initial-items-per-page","show-header","show-table-footer","show-items-per-page","blur-rows","onFilterTable","onProcessAdditionalFilters","onAdditionalFilterOptionSelected","onPaginate","onProcessChangeItemsPerPage","onSearch","onSortColumn"])])}const mt=b(Z,[["render",et]]);export{mt as P};
