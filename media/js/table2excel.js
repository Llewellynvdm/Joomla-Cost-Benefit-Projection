/*----------------------------------------------------------------------------------|  www.giz.de  |----/
	Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb 
/-------------------------------------------------------------------------------------------------------/

	@version		3.4.x
	@build			14th August, 2019
	@created		15th June, 2012
	@package		Cost Benefit Projection
	@subpackage		table2excel.js
	@author			Llewellyn van der Merwe <http://www.vdm.io>	
	@owner			Deutsche Gesellschaft für International Zusammenarbeit (GIZ) Gmb
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
	
/-------------------------------------------------------------------------------------------------------/
	Cost Benefit Projection Tool.
/------------------------------------------------------------------------------------------------------*/

jQuery.fn.table2excel = function() {
   
    var excelData = [];
    var headerArr = [];
    var el = this;

    //header
    var tmpRow = [];

    jQuery(el).find('th').each(function() {
		tmpRow[tmpRow.length] = formatData(jQuery(this).html());
	});

    rowMaker(tmpRow);

    // actual data
    jQuery(el).find('tr').each(function() {
        var tmpRow = [];
        jQuery(this).find('td').each(function() {
            tmpRow[tmpRow.length] = formatData(jQuery(this).html());
        });
        rowMaker(tmpRow);
    });
    var myExcel = excelData.join('\n');
    return myExcel;

    function rowMaker(tmpRow) {
        var tmp = tmpRow.join('') // to remove any blank rows
        // alert(tmp);
        if (tmpRow.length > 0 && tmp != '') {
            var mystr = tmpRow.join(' ##br## ');
            excelData[excelData.length] = mystr + " ####BR#### ";
        }
    }
    function formatData(input) {
        // replace " with â€œ
        var regexp = new RegExp(/["]/g);
        var output = input.replace(regexp, "â€œ");
        //HTML
        var regexp = new RegExp(/\<[^\<]+\>/g);
        var output = output.replace(regexp, "");
        if (output == "") return '';
        return output;
    }
};