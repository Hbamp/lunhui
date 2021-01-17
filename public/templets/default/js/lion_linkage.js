/**
 * linkage为多级联动通用化组件
 * 使用方法：
 * 在一组联动菜单的每一个select标签上加 class="linkage"
 * var linkage = new Linkage(obj,dataFrom,level,codes);
 * 使用linkage.init()初始化
 * 如需要获取多级联动字符串，调用linkage.dataQuery(codes,connector,start,end);
 * @param obj 多级联动组件载体的jquery对象，不可为空
 * @param dataFrom 多级联动组件的数据源，基于/static/js/linkage/datas/xx.js
 * @param level 多级联动组件的层级，不可为空
 * @param codes 带数据初始化时，传入的初始化数据，数据格式为数组，(例如["110000","110100","110101"])，可为空
 * @param connector 多级联动中间分隔符，例如:connector = '|',获取字符串为：北京|朝阳区,不传默认为'-'；
 * @param start 多级联动截取开始等级，例如:start = 1,获取字符串为：北京；
 * @param end 多级联动截取结束等级，配合start使用；例如:start = 1,end=2;获取字符串默认为：北京-朝阳区；
 * @returns 
 */

;(function(root, factory){
	if(typeof define === 'function' && define.amd){
		define('Linkage',['jquery','storage'], factory);
	}else{
		$(document).ready(function(){
			root.Linkage = factory(root.jQuery,root.storage);
		})
	}
})(this,function($,storage){
	//启动严格模式
	'use strict';
	
	var contextPath,urlPrefix = '/static/js/commons/data/',urlSuffix = '.js';
	
	function initContextPath(){
        contextPath = urlPrefix;
	}

	function initDatas(dataFrom){
		var result;
		if(dataFrom && typeof dataFrom === 'string'){
			if(storage.get(dataFrom)){
                try{
                    result = JSON.parse(storage.get(dataFrom));
                }catch(e){
                    return eval('('+storage.get(dataFrom)+')'); //ie7
                }

			}else{
				try{
					$.ajax({
                         url: contextPath + dataFrom + urlSuffix,
                         type: "GET",
                         cache: false,
                         async: false,
                         dataType: "script",
                         success: function(data){
                            result = linkageDatas;
                            if(result&&!isUnderIE(8)){
                                //保存到本地缓存
                                storage.save(dataFrom,JSON.stringify(result));
                            }
                         },
                         error:function(data,msg,e){
                             result = data;
                         }
                    });
				}catch(e){
					if(window.console){
                        console.assert('级联数据获取失败');
					}
					return null;
				}
			}
			return result;
		}
	}

	//田秋浩添加，IE6没有JSON方法，暂时没有合适的处理办法，使用这个处理，有新方法判断建议修改
	var isUnderIE = function(version) {
		version = version ? parseFloat(version) : 8.0;
		// 不是IE
		if (navigator.appName.indexOf('Microsoft Internet Explorer') == -1) {
			return false;
		}
		var ver = navigator.userAgent.toLowerCase().match(/msie ([\d.]+)/)[1];
		return parseFloat(ver) < version;
	}
	
	function Linkage(obj,dataFrom,level,codes){
		
		var datas;
		initContextPath();
		if(arguments.length === 2){
			datas = initDatas(arguments[0]);
			level = arguments[1];
		}else{
			datas = initDatas(dataFrom);
		}

		function update(){
			var childrenData = datas;
			for(var i=0; i<level; i++){
				if((i+1) == level){
					appendOptionEnd(findSelect(i),childrenData,codes[i]);
				}else{
					appendOption(findSelect(i),childrenData,codes[i]);
				}
				changeOption(i,childrenData);
				childrenData = findChildrenData(childrenData,codes[i]);
			}
		}
		
		function initialize(){
			if(level == 1){
				appendOptionEnd(findSelect(0),datas);	
			}else{
				appendOption(findSelect(0),datas);	
				changeOption(0,datas);					
			}
		}
		
		
		function changeOption(index,data){
			var obj = findSelect(index);
			var obj2 = findSelect(index+1);
			obj.change(function(){
				var num = $(obj).val();
				removeAndAppend(index+1);
				if(num == null || num == ""){
					return;
				}
				var childrenData = findChildrenData(data,num);
				if(index + 2 == level){
					appendOptionEnd(obj2,childrenData);
				}else{
					appendOption(obj2,childrenData);
				}
				if(index + 2 < level){
					changeOption(index+1,childrenData);
				}
			});
		}
		
		function appendOption(obj,data,code){
			for(var key in data){
				var num = key;
				var name;
				for(var key in data[key]){
					name = key;
				}
				obj.append("<option value='" + num + "'>" + name + "</option>");
				if(num == code){
					setTimeout(function(){  
						obj.find("option").each(function(index,element){
							if($(this).val()==code){
								$(this).attr('selected','selected'); 
							}
						})
				    },1);  
				}
			}
		}
		
		function appendOptionEnd(obj,data,code){
			for(var key in data){
				obj.append("<option value='" + key + "'>" + data[key] + "</option>");
				if(key == code){
					setTimeout(function(){  
						obj.find("option").each(function(index,element){
							if($(this).val()==code){
								$(this).attr('selected','selected'); 
							}
						})
				    },1);  
				}
			}
		}
		
		function findChildrenData(data,code){
			var childrenData;
			if(typeof data !== 'undefined' && code != null && code != ""){
				for(var key in data[code]){
					childrenData = (data[code])[key];
				}
			}
			return childrenData;
		}
		
		function findSelect(index){
			var dom = obj.find("select.linkage").eq(index);
			return dom;
		}
		
		function removeAndAppend(index){
			for(var i=index; i<level; i++){
				findSelect(i).empty().append("<option value=''>--请选择--</option>");
			}
		}

		function query(data,code){
			var name;
			for(var key in data){
				var num = key ;
				if(num == code){
					for(var key in data[key]){
						name = key;
					}
				}
			}
			return name;
		}
		
		function queryEnd(data,code){
			var name;
			for(var key in data){
				if(key == code){
					name = data[key];
				}
			}
			return name;
		}
		
		this.init = function(){
			if(codes != null && codes != ""){
				update();	
			}else{
				initialize();
			}
		}
		
		this.dataQuery = function(codes,connector,start,end){
			var childrenData = datas, data = "", dataArr = [], con = '-', codeLevel = codes.length;
			if(arguments.length >= 2 && typeof(arguments[1]) === "string"){
				con = arguments[1];
			}
			for(var i=0; i<level; i++){
				if(codeLevel >= (i + 1)){
					if((i+1) === level){
						dataArr.push(queryEnd(childrenData,codes[i]));
					}else{
						dataArr.push(query(childrenData,codes[i]));
					}
					childrenData = findChildrenData(childrenData,codes[i]);
				}
			}
			
			start = start || 1;
			end = end || dataArr.length;
			
			if(arguments.length === 2 && typeof(arguments[1]) === 'number' && arguments[1] <= level){
				start = arguments[1];
				end = arguments[1];
			}
			
			if(arguments.length === 3){
				if(typeof(arguments[1]) === "string" && typeof(arguments[2]) === 'number' && arguments[2] <= level){
					start = arguments[2];
					end = arguments[2];
				}
				if(typeof(arguments[1]) === 'number' && typeof(arguments[2]) === 'number' &&  arguments[2] <= level && arguments[1] < arguments[2]){
					start = arguments[1];
					end = arguments[2];
				}
			}
			if(start && end){
				data = dataArr[start-1] ? dataArr[start-1] : "";
				if(start < end){
					for(var i = start; i <= end-1; i++){
						if(con){
							data += dataArr[i] ? (con + dataArr[i]) : "";
						}else{
							data += dataArr[i] ? dataArr[i] : "";
						}
					}
				}
			}
			return data || '---';
		}
	}

	return Linkage;
});

var linkageHandler = function(reb){
	$(document).ready(function(){
		var timer = setInterval(function(){
			if(Linkage&&(typeof reb === "function")){
				reb();
				clearInterval(timer);
			}
		},100);
	})
};
