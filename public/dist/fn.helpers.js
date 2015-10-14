/* Add your custom javascript functions here */
      
      function str_singular(str){
        var res = '<?php echo str_singular("'+str+'");?>';
        return res;
      }

      function str_plural(str){
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
          }
          return str_singular( str )+suf;
        }catch(Exception){
          console.log(Exception);
        }
      }