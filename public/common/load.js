function loadGet(purl,params,callback){
    var result= '';
    $.ajax({
        async: callback ? true : false,
        timeout: 150000,
        type: 'GET',
        url: purl,
        data: params,
        processData: true,
        datatype: 'json',
        beforeSend: function(XMLHttpRequest) {
            
        },
        success: function(json) {
            if (typeof json === 'string') {
                if (json.length < 1) {
                    console.log('json无效，数据为空');
                    return;
                }
                try {
                    result = eval('(' + json + ')');
                } catch (e) {
                    if (typeof console.log != 'undefined') {
                        console.log(json);
                    }
                    console.log('服务器返回数据无法解析');
                    return false;
                }
            } else {
                result = json;
            }

            // 解包
            if (callback){
                callback(result);
            }
            
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);               
            }
        
    });
    if(!callback){
        return result;
    }
}


function loadPOST(purl,params,callback){
    var result= '';
    $.ajax({
        async: callback ? true : false,
        timeout: 150000,
        type: 'POST',
        url: purl,
        data: params,
        processData: true,
        datatype: 'json',
        beforeSend: function(XMLHttpRequest) {
            
        },
        success: function(json) {

            if (typeof json === 'string') {
                if (json.length < 1) {
                    console.log('json无效，数据为空');
                    return;
                }
                try {
                    result = eval('(' + json + ')');
                } catch (e) {
                    if (typeof console.log != 'undefined') {
                        console.log(json);
                    }
                    console.log('服务器返回数据无法解析');
                    return false;
                }
            } else {
                result = json;
            }

            // 解包
            if (callback)
                callback(result);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);               
            }
        
    });
    if(!callback){
        return result;
    }
}