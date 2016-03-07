/*! fn.helpers.js | (c) 2015 ECE Marketing */
/* Add your custom javascript functions here */

$.fn.hasParent = function(a) {
    return this.filter(function() {
        return !!$(this).closest(a).length;
    });
};

function str_singular(str){
    $.ajax({
        url: '/api/str_singular/'+str,
        type: 'get'
    }).done(function(data){
        if( data != "" ){
            return data;
        }
    });
}

function str_plural(str){
    try{
        var lastChar = "", replacement = "", new_str;

        // lastChar = substr(str, strlen( str ) - 2);
        // new_str = substr(str, 0, strlen( str ) - 2);

        lastChar = str.substr(str.length-2);
        new_str = str.substr(0, str.length-2);


        if( lastChar == "um" ) replacement = "a";
        if( lastChar == "fe" ) replacement = "ves";
        if( lastChar == "us" ) replacement = "i";
        if( lastChar == "ch" )  return str+"es";

        if( replacement != "" ) return new_str+replacement;



        // lastChar = substr(str, strlen(str) -1 );
        lastChar = str.substr(str.length-1);
        // new_str = substr(str, 0, strlen( str ) - 1);
        new_str = str.substr(0, str.length-1);

        if( lastChar == "f" ) replacement = "ves";

        if( lastChar == "y" ) replacement = "ies";

          // return new_str+replacement;

        if( lastChar == "s" || lastChar == "x" ){
          return str+"es";
        }else{
          return str+"s";
        }


        if( replacement == "" ){
          new_str = str;
        }

        return new_str+replacement;
    }catch(Exception){
        console.log(Exception);
    }
} 

function str_auto_plural(str, quantity){
    try{
        var pos = str.indexOf("(");
        var suf = "";
        if( pos !== -1 ){
            str = $.trim( str.substr(0, pos) );
            suf = $.trim( str.substr(pos) );
            console.log("str: "+str+" suf: "+suf);
        }

        if( quantity > 1 ){
            return str_plural( str )+suf;
        }else{
            return str+suf;
        }
    }catch(Exception){
        console.log(Exception);
    }
}

// get $_GET param values
$.getUrlParam  = function (name){
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
};

function generate_random_string(length){
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < length; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function peso(){
    return '&#x20B1;';
}

function is_array(mixed_var) {
  //  discuss at: http://phpjs.org/functions/is_array/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Legaev Andrey
  // improved by: Onno Marsman
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Nathan Sepulveda
  // improved by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: Cord
  // bugfixed by: Manish
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //        note: In php.js, javascript objects are like php associative arrays, thus JavaScript objects will also
  //        note: return true in this function (except for objects which inherit properties, being thus used as objects),
  //        note: unless you do ini_set('phpjs.objectsAsArrays', 0), in which case only genuine JavaScript arrays
  //        note: will return true
  //   example 1: is_array(['Kevin', 'van', 'Zonneveld']);
  //   returns 1: true
  //   example 2: is_array('Kevin van Zonneveld');
  //   returns 2: false
  //   example 3: is_array({0: 'Kevin', 1: 'van', 2: 'Zonneveld'});
  //   returns 3: true
  //   example 4: is_array(function tmp_a(){this.name = 'Kevin'});
  //   returns 4: false

  var ini,
    _getFuncName = function(fn) {
      var name = (/\W*function\s+([\w\$]+)\s*\(/)
        .exec(fn);
      if (!name) {
        return '(Anonymous)';
      }
      return name[1];
    };
  _isArray = function(mixed_var) {
    // return Object.prototype.toString.call(mixed_var) === '[object Array]';
    // The above works, but let's do the even more stringent approach: (since Object.prototype.toString could be overridden)
    // Null, Not an object, no length property so couldn't be an Array (or String)
    if (!mixed_var || typeof mixed_var !== 'object' || typeof mixed_var.length !== 'number') {
      return false;
    }
    var len = mixed_var.length;
    mixed_var[mixed_var.length] = 'bogus';
    // The only way I can think of to get around this (or where there would be trouble) would be to have an object defined
    // with a custom "length" getter which changed behavior on each call (or a setter to mess up the following below) or a custom
    // setter for numeric properties, but even that would need to listen for specific indexes; but there should be no false negatives
    // and such a false positive would need to rely on later JavaScript innovations like __defineSetter__
    if (len !== mixed_var.length) { // We know it's an array since length auto-changed with the addition of a
      // numeric property at its length end, so safely get rid of our bogus element
      mixed_var.length -= 1;
      return true;
    }
    // Get rid of the property we added onto a non-array object; only possible
    // side-effect is if the user adds back the property later, it will iterate
    // this property in the older order placement in IE (an order which should not
    // be depended on anyways)
    delete mixed_var[mixed_var.length];
    return false;
  };

  if (!mixed_var || typeof mixed_var !== 'object') {
    return false;
  }

  // BEGIN REDUNDANT
  this.php_js = this.php_js || {};
  this.php_js.ini = this.php_js.ini || {};
  // END REDUNDANT

  ini = this.php_js.ini['phpjs.objectsAsArrays'];

  return _isArray(mixed_var) ||
  // Allow returning true unless user has called
  // ini_set('phpjs.objectsAsArrays', 0) to disallow objects as arrays
  ((!ini || ( // if it's not set to 0 and it's not 'off', check for objects as arrays
    (parseInt(ini.local_value, 10) !== 0 && (!ini.local_value.toLowerCase || ini.local_value.toLowerCase() !==
      'off')))) && (
    Object.prototype.toString.call(mixed_var) === '[object Object]' && _getFuncName(mixed_var.constructor) ===
    'Object' // Most likely a literal and intended as assoc. array
  ));
}

function allowNumericOnly(element){
    element.keydown(function (e) {

        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
             (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
             (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
             return;
        }
        
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    element.change(function (e){
        var val = parseFloat($(this).val());
        var max = $(this).attr("data-max");
        var min = $(this).attr("data-min");

        if( typeof max !== 'undefined' ){
            if( val >  parseFloat(max) ){
                $(this).val(max);
            }
        }

        if( typeof min !== 'undefined' ){
            if( val <  parseFloat(min) || $(this).val() == "" ){
                $(this).val(min);
            }
        }
    });
}

function limitStr(filename, max){
    if( filename.length <= max )
        return filename;
    return filename.substring(0, 35)+"...";
}

function getArrayIndexForKey(arr, key, val){
    for(var i = 0; i < arr.length; i++){
        if(arr[i][key] == val)
            return i;
    }
    return -1;
}

// add a javascript equivalent of php 'ucfirst' function 
String.prototype.ucfirst = function(){
  return this.charAt(0).toUpperCase() + this.slice(1);
}