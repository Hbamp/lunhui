/**
 * 浏览器存储实用工具类，用来保留一些状态数据，在浏览器刷新时不清空 支持ie6 7 8 9 10 11
 */
;(function(root, factory) {
    if (typeof define === 'function' && define.amd) {
        define('storage', factory);
    } else {
        root.storage = factory();
    }
})(this, function() {
    var isUnderIE = function(version) {
        version = version ? parseFloat(version) : 8.0;
        // 不是IE
        if (navigator.appName.indexOf('Microsoft Internet Explorer') == -1) {
            return false;
        }
        var ver = navigator.userAgent.toLowerCase().match(/msie ([\d.]+)/)[1];
        return parseFloat(ver) < version;
    }

    var UserDate = function() {
        var data, name;

        var init = function(n) {
            if (!data) {
                name = n ? n : window.location.hostname;
                try {
                    data = document.createElement('input');
                    data.type = "hidden";
                    data.style.display = "none";
                    data.addBehavior("#default#userData");

                    document.appendChild(data);
                    var expires = new Date();
                    expires.setDate(expires.getDate() + 365);
                    data.expires = expires.toUTCString();
                } catch (e) {
                    if(window.console){
                        console.assert('userdata创建失败');
                    }
                    return null;
                }
                return this;
            }
        };
        var setItem = function(key, value) {
            data.load(name);
            data.setAttribute(key, value);
            data.save(name);
        };
        var getItem = function(key) {
            data.load(name);
            return data.getAttribute(key);
        };
        var removeItem = function(key) {
            data.load(name);
            data.removeAttribute(key);
            data.save(name);
        };

        return {
            init : init,
            setItem : setItem,
            getItem : getItem,
            removeItem : removeItem
        }
    }();

    //数据绑定对象
    var storage = isUnderIE(8) ? UserData.init() : window.localStorage;

    //调用缓存存储
    var save = function(name, data){
        if(window.console){
            console.assert(storage,'浏览器不支持本地存储!');
        }
        if(arguments.length != 2 || typeof name != 'string'|| data == null){
            if(arguments.length == 1 && typeof name === 'object'){
                for(var key in name){
                    if(key && key.hasOwnProperty(key)){
                        storage.setItem(key, name[key]);
                    }
                }
            }
            return;
        }
        storage.setItem(name, data);
    };

    var get = function(name,handler){
        var data = storage.getItem(name);
        handler = typeof handler === 'undefined' ? function(){
            data = data == 'true' ? true : (data == 'false' ? false : data);
        } : handler;
        handler(data);
        return data;
    };
    var remove = function (name) {
        return this.get(name,function () {
            storage.removeItem(name);
        });
    };
    var getAll = function () {

    };
    var clear = function () {

    };
    return {
        //绑定对象
        save: save,
        //取出对象
        get: get,
        //解绑对象（返回解绑的对象）
        remove: remove,
        //获取所有对象
        getAll: getAll,
        //清空所有对象
        clear: clear
    }

});