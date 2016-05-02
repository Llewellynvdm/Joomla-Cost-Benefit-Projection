<?php
/**
* 
* 	@version 	1.0.5 October 6, 2015
*	@author		Llewellyn van der Merwe <https://www.vdm.io/> (forked - <https://code.google.com/p/php-class-for-google-chart-tools/>) 
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
*
**/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class Chartbuilder
{
        
        private static $_first = true;
        public static $_count = 0;
        
        private $_chartType;
        
        private $_data;
        private $_dataType;
        private $_skipFirstRow;
        
        /**
         * sets the chart type and updates the chart counter
         */
        public function __construct($chartType, $skipFirstRow = false){
                $this->_chartType = $chartType;
                $this->_skipFirstRow = $skipFirstRow;
                self::$_count++;
        }
        
        /**
         * loads the dataset and converts it to the correct format
         */
        public function load($data, $dataType = 'json'){
                $this->_data = ($dataType != 'json') ? $this->dataToJson($data) : $data;
        }
        
        /**
         * load jsapi
         */
        private function initChart(){
                self::$_first = false;
                
                $output = '';
                // start a code block
                /*$output .= '<script type="text/javascript" src="https://www.google.com/jsapi"></script>'."\n";*/
                $output .= '<script type="text/javascript">google.load(\'visualization\', \'1.0\', {\'packages\':[\'corechart\']});</script>'."\n";
                
                return $output;
        }
        
        /**
         * draws the chart
         */
        
        public function draw($div, Array $options = array()){
                $output = '';
                
                if(self::$_first)$output .= $this->initChart();
                				
                // start a code block
                $output .= '<script type="text/javascript">';

                // set callback function
                $output .= 'google.setOnLoadCallback(drawChart' . self::$_count . ');';
					              
                // create callback function
                $output .= 'function drawChart' . self::$_count . '(){';
                
                $output .= 'var data = new google.visualization.DataTable(' . $this->_data . ');';
				
				// make chart resize
				$output .= "var width = 0;
							
							var graphHeight". self::$_count ." = 0;
							var graphWidth". self::$_count ." = 0;
							jQuery(window).ready(function() {
								width = jQuery(window).width();
								if (width < 1000){
									sizeGraph". self::$_count ."(true,width);
								} else if (width > 1000) {
									sizeGraph". self::$_count ."(false);
								}
								";
								

					// draw on tab change
					if (isset($options['tabclickdraw']))
					{
						$output .= "jQuery('.".$options['tabclickdraw']."').on('click', function(){
									width = jQuery(window).width();
									if (width < 1000){
										sizeGraph". self::$_count ."(true,width);
									} else if (width > 1000) {
										sizeGraph". self::$_count ."(false);
									}
								});";
						unset($options['tabclickdraw']);
					}

					 $output .= "
								jQuery('.icon').on('click', function(){
									width = jQuery(window).width();
									if (width < 1000){
										sizeGraph". self::$_count ."(true,width);
									} else if (width > 1000) {
										sizeGraph". self::$_count ."(false);
									}
								});
							});
							
							jQuery(window).resize(function () {
								width = jQuery(window).width();
								if (width < 1000){
									sizeGraph". self::$_count ."(true,width);
								} else if (width > 1000) {
									sizeGraph". self::$_count ."(false);
								}
							});
							
							function sizeGraph". self::$_count ."(set, width) {
								if(set){
									var setTo = width/1200;
									graphHeight". self::$_count ." = setTo*".$options['height'].";
									graphWidth". self::$_count ." = setTo*".$options['width'].";
								} else {
									graphHeight". self::$_count ." = ".$options['height'].";
									graphWidth". self::$_count ." = ".$options['width'].";
								}
								
							";
												
                // set the options
                $output .= 'var options = ' . json_encode($options) . '; options["height"]=graphHeight'. self::$_count .'; options["width"]=graphWidth'. self::$_count .';';
                
                // create and draw the chart
                $output .= 'var chart = new google.visualization.' . $this->_chartType . '(document.getElementById(\'' . $div . '\'));';
                $output .= 'chart.draw(data, options);';
                
                $output .= '}} </script>' . "\n";
                return $output;
        }
                
        /**
         * substracts the column names from the first and second row in the dataset
         */
        private function getColumns($data){
                $cols = array();
                foreach($data[0] as $key => $value){
                        if(is_numeric($key)){
                                if(is_string($data[1][$key])){
                                        $cols[] = array('id' => '', 'label' => $value, 'type' => 'string');
                                } else {
                                        $cols[] = array('id' => '', 'label' => $value, 'type' => 'number');
                                }
                                $this->_skipFirstRow = true;
                        } else {
                                if(is_string($value)){
                                        $cols[] = array('id' => '', 'label' => $key, 'type' => 'string');
                                } else {
                                        $cols[] = array('id' => '', 'label' => $key, 'type' => 'number');
                                }
                        }
                }
                return $cols;
        }
        
        /**
         * convert array data to json
         * info: https://code.google.com/intl/nl-NL/apis/chart/interactive/docs/datatables_dataviews.html#javascriptliteral
         */
        private function dataToJson($data){
                $cols = $this->getColumns($data);
                
                $rows = array();
                foreach($data as $key => $row){
                        if($key != 0 || !$this->_skipFirstRow){
                                $c = array();
                                foreach($row as $v){
                                        $c[] = array('v' => $v);
                                }
                                $rows[] = array('c' => $c);
                        }
                }
                
                return json_encode(array('cols' => $cols, 'rows' => $rows));
        }
        
}