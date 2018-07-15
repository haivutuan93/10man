/*madara callback for visual composer*/
function compareTableCallbackColumns() {
	var $ = jQuery;
	$columns = $( '.wpb_vc_param_value[name=column_size]', this.$content );
	var sourceModelID = this.model.id;
	var $elementChangeWidth = $('[data-model-id="'+(sourceModelID)+'"][data-element_type="c_column"]');
	
	var $buttonClose = this.$content.parents('.vc_ui-panel-window-inner').find('[data-vc-ui-element="button-close"]');
	var $buttonSave = this.$content.parents('.vc_ui-panel-window-inner').find('[data-vc-ui-element="button-save"]');
	
	var defaultValue = $columns.val();
	
	function setColumnsChange(objectElms, valDefault) {
		
		if($elementChangeWidth.length==0) {
			return;
		}
		
		$elementChangeWidth.removeClass('col-extend col-extend-md-1 col-extend-md-2 col-extend-md-3 col-extend-md-4 col-extend-md-5 col-extend-md-6 col-extend-md-7 col-extend-md-8 col-extend-md-9 col-extend-md-10 col-extend-md-11 col-extend-md-12 col-extend-20percent col-extend-14percent col-extend-12percent col-extend-11percent col-extend-10percent col-extend-09percent');
		
		var $this = objectElms;
		var strVal = '';
		if($this!='') {
			strVal=$this.val();
		}else{
			strVal=valDefault;
		};		
		switch(strVal){
			case '2':
				$elementChangeWidth.addClass('col-extend col-extend-md-2');
				break;
			case '3':
				$elementChangeWidth.addClass('col-extend col-extend-md-3');
				break;
			case '4':
				$elementChangeWidth.addClass('col-extend col-extend-md-4');
				break;
			case '6':
				$elementChangeWidth.addClass('col-extend col-extend-md-6');
				break;			
			default:
				break;					
		}
	}
	
	$columns.change( function(){		
		setColumnsChange($(this),'');			
	})
	.trigger('change');
	
	$buttonClose.on('click', function(){
		if(defaultValue!=$columns.val()) {
			//check value, restore default
			setColumnsChange('', defaultValue);
		}
	});
	
	$buttonSave.on('click', function(){
		//check value, set new default
		defaultValue = $columns.val();
	});
};
(function($){
	function initColumns(elems) {
		elems.each(function(index, element) {
			var $this = $(this);
			var dataModelID = $this.attr('data-model-id');
			var $elementChangeWidth = $('[data-model-id="'+(dataModelID)+'"][data-element_type="c_column"]');
			
			var $itemWidth = $this.find('.admin_label_column_size');
			var itemWidthString = $itemWidth.text();
			
			if(itemWidthString!='') {
				if(itemWidthString.indexOf(": 2")>0) {
					$elementChangeWidth.addClass('col-extend col-extend-md-2');
				}else if(itemWidthString.indexOf(": 3")>0){
					$elementChangeWidth.addClass('col-extend col-extend-md-3');
				}else if(itemWidthString.indexOf(": 4")>0){
					$elementChangeWidth.addClass('col-extend col-extend-md-4');
				}else if(itemWidthString.indexOf(": 6")>0){
					$elementChangeWidth.addClass('col-extend col-extend-md-5');
				}
			};			
		});
	}
	
	$(document).ready(function(e) {
		$('[data-element_type="c_column"] .vc_control.column_clone').live('click', function(){
			var $this = $(this);
			setTimeout(function() {
				initColumns($('[data-element_type="c_column"]:not(.col-extend)'));
			},200);
		});
        $(window).on('load', function(){
			setTimeout(function() {
				initColumns($('[data-element_type="c_column"]'));
			},100);
		});
    });
}(jQuery));
/*madara callback for visual composer*/