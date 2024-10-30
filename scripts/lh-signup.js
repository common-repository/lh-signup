(function() {
    
    function isNumeric(str) {
        if (typeof str != "string") return false; // we only process strings!  
  
        return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
         !isNaN(parseFloat(str)); // ...and ensure strings of whitespace fail
         
    }
    
    
    function slugify(text) {
      return text.toString().toLowerCase().trim()
    	.normalize('NFD') 				 // separate accent from letter
    	.replace(/[\u0300-\u036f]/g, '') // remove all separated accents
    	.replace(/\s+/g, '-')            // replace spaces with -
    	.replace(/&/g, '-and-')          // replace & with 'and'
    	.replace(/[^\w\-]+/g, '')        // remove all non-word chars
    	.replace(/\-\-+/g, '-');        // replace multiple '-' with single '-'
    }
    
    function boot(){
        
        if (document.getElementById("lh_signup-form")){

            document.getElementById("lh_signup-nonce").value = document.getElementById("lh_signup-form").getAttribute("data-lh_signup-nonce");

        }
        
        if (document.getElementById("lh_signup-site_template")){
            
            var selector = document.getElementById("lh_signup-site_template");
            
            if (document.getElementById("lh_signup-template-" + selector.value)){
                
                document.getElementById("lh_signup-template_preview").innerHTML = '';
                        
                clone = document.getElementById("lh_signup-template-" + selector.value).content.cloneNode(true);
                        
                document.getElementById("lh_signup-template_preview").appendChild(document.importNode(clone, true));
                        
            } 
            
            selector.addEventListener("change", function() {
                
                if (isNumeric(selector.value)){
                    
                    var clone;
                    
                    document.getElementById("lh_signup-template_preview").innerHTML = '';
                    
                    if (document.getElementById("lh_signup-template-" + selector.value)){
                        
                        clone = document.getElementById("lh_signup-template-" + selector.value).content.cloneNode(true);
                        
                        document.getElementById("lh_signup-template_preview").appendChild(document.importNode(clone, true));
                        
                    } 
                
                    
                    
                } else {
                    
                    document.getElementById("lh_signup-template_preview").innerHTML = '';
                    
                }
                    
                
            });
            
            
            
        }
        
        if (document.getElementById("lh_signup-site_slug")){
            
            var site_slug = document.getElementById("lh_signup-site_slug");
            
            site_slug.addEventListener('keyup', function(event) {
  
                site_slug.value = slugify(event.target.value);
            
            });
            
        }
        
        
        
        
        
    }
    
    boot();

})();