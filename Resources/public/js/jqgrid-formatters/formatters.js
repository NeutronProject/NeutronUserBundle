jQuery.extend(jQuery.fn.fmatter , {
    datetimeFormatter : function(cellvalue, options, rowdata) {
    	if(null !== cellvalue){
    		 return cellvalue.date;
    	}
    	
    	return '';
       
    },
	arrayFormatter : function(cellvalue, options, rowdata) {
		if(jQuery.isArray(cellvalue)){
			return cellvalue.join(',');
		}
	}
});
